<?php

namespace App\Http\Livewire;

use App\Models\Entrada;
use App\Models\Pagina;
use App\Models\Tag;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ShowPagina extends Component
{
    public $book;
    public $date;
    public $pagina;
    public $entradas;
    public $tags;

    protected $listeners = [
        'refrescar-pagina' => '$refresh',
        'guardar-entrada' => 'guardarEntrada',
        'ordenar-entradas' => 'ordenarEntradas',
        'completar-entrada' => 'completarEntrada',
        'eliminar-entrada' => 'eliminarEntrada',
        'obtener-tags' => 'obtenerTags',
        'ordenar-tags' => 'ordenarTags',
        'cambiar-dia' => 'cambiarDia'
    ];

    // : Métodos para obtener, renderizar y generar páginas 

    public function mount(){
        // Obtener id de libro de la sesión creada al redirigir desde la vista libro.
        if ($this->book == null)
            $this->book = session()->get('idLibro');

        // Obtengo la fecha para esta página que usaré para determinar qué mes mostrar en la vista calendario más adelante.
        if ($this->date == null)
            $this->date = session()->get('fechaPagina');
    }

    public function render()
    {
        // Seleccionar página de hoy.
        $pagina = $this->obtenerPaginaHoy();
        
        /** Si no existe:
         *  Signfifica que es la primera vez que entramos hoy en este libro.
         *  Genero la página de hoy, o todas las páginas desde la última vez que se accedió. 
         *  Y ahora sí selecciono la página de hoy. */
        if($pagina == null){
            $this->generarPaginas();
            $pagina = $this->obtenerPaginaHoy();
        }

        // Asigno la página actual al campo página que usaré más adelante.
        $this->pagina = $pagina;
        
        // Obtengo las entradas de la página.
        $this->entradas = $pagina->entradas()->with('tags')->orderByPivot('orden', 'asc')->get();
        
        // Obtengo las tags del libro
        $this->tags = Tag::where('libro_id', $this->book)->get();
    
        return view('livewire.show-pagina');
    }

    // Función para obtener la página de hoy, en caso de devolver null la primera vez; se volverá a llamar tras generar las páginas faltantes.
    private function obtenerPaginaHoy() : Pagina|null {
        $fechaHoy =  $this->date->toDateString();
        return Pagina::whereRaw("DATE(created_at) = '$fechaHoy'")->where('libro_id', $this->book)->first();
    }

    /**
     * Obtengo la última página que se haya registrado en este libro y sus entradas.
     *      Si no existe una página anterior, simplemente creo una página vacía con la fecha de hoy.
     *      Si sí existe un a pagina anterior:
     *          Creo páginas para todos los días que no se ha accedido al libro, y para hoy.
     *          Todas esas páginas se rellenan con las entradas no completadas de la última página existente. 
     * */
    private function generarPaginas() {
        // Consulta SQL equivalente: select * from paginas where libro_id = 1 order by created_at limit 1;
        $ultimaPagina = Pagina::where('libro_id', $this->book)->orderBy('created_at', 'desc')->first();
        
        // Genero la primera página si el libro no tiene aún páginas.
        if (!$ultimaPagina){
            Pagina::create([
                'libro_id' => $this->book,
            ]);
            return;
        }

        // Genero un objeto de intervalo de fechas de 1 día, que usaré a continuación.
        $intervaloDia = new DateInterval('P1D');
        
        // Obtengo el día después de la fecha de la última página.
        $fechaUltimaPagina = new DateTime($ultimaPagina->created_at);
        $fechaUltimaPagina->add($intervaloDia);

        // Obtengo la fecha de hoy.
        $fechaActual = new DateTime();
        $fechaActual->add($intervaloDia);

        // Obtengo el periodo de días entre el día siguiente al de la última página y hoy.
        $periodoDias = new DatePeriod($fechaUltimaPagina, $intervaloDia, $fechaActual);

        // Obtengo todas las entradas relacionadas con la última página y que no estén completadas.
        $idsEntradas = $ultimaPagina->entradas()->whereNull('compleated_at')->pluck('entradas.id')->toArray();

        // Creo páginas entre la página del último día y el día actual, y asigno las entradas de la página del último día a todos los días hasta hoy.
        // (Si no hemos accedido al libro en los últimos días, no hemos podido completar las entradas durante estos días asique simplemente asigno las entradas a los días intermedios).
        foreach($periodoDias as $dia){
            $pagina = Pagina::create([
                'libro_id' => $this->book,
                'created_at'=> $dia->format('Y-m-d')
            ]);
            $pagina->entradas()->sync($idsEntradas);
        }
    }

    // : Método para cambiar de día 
    public function cambiarDia($anterior){
        $fechaSesion = session()->get('fechaPagina');
        if ($anterior){
            $fechaSesion->subDay();
            session(['fechaPagina' => $fechaSesion]);
        } else {
            if ($fechaSesion->isToday())
                $this->dispatchBrowserEvent('dia-futuro');
            else {
                $fechaSesion->addDay();
                session(['fechaPagina' => $fechaSesion]);
            }
        }
        
        return redirect()->route('pagina.show');
    }

    // : Métodos para gestionar entradas 
    public function guardarEntrada($idEntrada, $contenido){
        // Itero por todas las entradas obtenidas de la base de datos, comparando IDs, si encuentro una ID que coincida, la entrada encontrada es una entrada a editar.
        // Hago esto para evitar realizar otra consulta a la base de datos, lo cual, en un principio, debería ser más eficiente.
        foreach ($this->entradas as $entrada){
            if ($entrada->id == explode("-", $idEntrada)[0]) {
                Entrada::where('id', $entrada->id)->update(['contenido' => $contenido]);
                return;
            }
        }

        // Si el bucle ha acabado y no se ha encontrado una entrada que coincida, significa que la entrada a guardar es nueva.
        // Entonces creo una nueva entrada con el contenido especificado y envío al front-end la ID de la nueva entrada.
        if($contenido != null){
            $entrada = Entrada::create(['contenido' => $contenido]);
            $entrada->paginas()->attach($this->pagina);
            $this->dispatchBrowserEvent('nueva-id', ['nuevaId' => $entrada->id]);
        }

        return redirect()->route('pagina.show');
    }

    public function ordenarEntradas($entradas, $movil){
        // Obtengo el array de IDs de entradas desde el front-end.
        // El orden lo establecerá la clave de cada posición mientras que el valor indica la ID de cada entrada.
        $this->pagina->entradas()->syncWithoutDetaching(
            collect($entradas)->mapWithKeys(function ($idEntrada, $orden) {
                return [$idEntrada => ['orden' => $orden]];
            })
        );

        if ($movil)
            return redirect()->route('pagina.show');
    }

    public function completarEntrada($idEntrada){
        foreach ($this->entradas as $entrada){
            if ($entrada->id == explode("-", $idEntrada)[0]) {
                Entrada::where('id', $entrada->id)->update(['compleated_at' => Carbon::now()]);
                return redirect()->route('pagina.show');
            }
        }

        return redirect()->route('pagina.show');
    }

    public function eliminarEntrada($idEntrada){
        foreach($this->entradas as $entrada){
            if($entrada->id == $idEntrada)
                $entrada->delete();
        }

        return redirect()->route('pagina.show');
    }

    // : Métodos para gestionar Tags

    public function ordenarTags($idEntrada, $idsTags){
        $entradaObjetivo = null;
        foreach($this->entradas as $entrada){
            if ($entrada->id == $idEntrada){
                $entradaObjetivo = $entrada;
                break;
            }
        }

        if ($entradaObjetivo != null){
            $entradaObjetivo->tags()->syncWithoutDetaching(
                collect($idsTags)->mapWithKeys(function ($idTag, $orden){
                    return [$idTag => ['orden' => $orden]];
                })
            );
        }

        $this->emit('refrescar-entrada');
    }
}