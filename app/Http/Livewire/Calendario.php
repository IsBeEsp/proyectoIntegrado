<?php

namespace App\Http\Livewire;

use App\Models\Entrada;
use App\Models\Libro;
use App\Models\Pagina;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Calendario extends Component
{
    // $ Para la generación del calendario.
    public $book;
    public $date;

    protected $listeners = [
        'obtenerEntradas',
        'verDetalles',
        'update',
        'cancelar',
        'cambiarMes'
    ];

    // $ Para la edición de entradas desde el calendario.
    public $openEntrada  = false;
    public $contenido;
    public Entrada $entrada;
    public $tags;

    protected $rules = [
        'entrada.contenido'=>['required', 'string', 'min:1'],
    ];

    public function render()
    {
        // Obtener libro del calendario a acceder.
        if(session()->get('idLibro') != null)
            $this->book = session()->get('idLibro');
        else   
            abort('404');

        // Obtener fecha.
        $this->date = session()->get('fechaCalendario');
        
        if ($this->date == null)
            $this->date = session()->get('fechaPagina');

        return view('livewire.calendario');
    }

    public function obtenerEntradas()
    {
        // Obtengo todas las páginas pertenecientes al libro y mes en concreto.
        $paginas = null;
        $libro = Libro::where('id', $this->book)->first();
        if ($libro->user_id == auth()->user()->id)
            $paginas = Pagina::where('libro_id', $this->book)->whereMonth('created_at', $this->date->month)->with('entradas')->get();

        // Obtengo las entradas pertenecientas a cada página, y las almaceno en un array de días que luego enviaré al front.
        $paginasEntradas = [];
        if ($paginas != null){
            foreach ($paginas as $pagina) {
                $arrayEntradas = [];
                foreach ($pagina->entradas as $entrada) {
                    
                    if ($entrada->tags != null){
                        $tag = $this->obtenerPrimeraTag($entrada->tags);
                        
                        $completada = ($entrada->compleated_at != null) ? true : false;
                        
                        if ($tag != null)
                            $arrayEntradas[] = $entrada->id . '-' . $tag->color . '-' . $tag->nombre . '-' . $completada;
                    }
                }
                if ($arrayEntradas != null)
                    $paginasEntradas[Carbon::parse($pagina->created_at)->day] = $arrayEntradas;
            }
        } else
            abort('404');

        $this->dispatchBrowserEvent('recibirEntradas', ['paginasEntradas' => $paginasEntradas, 'fecha' => $this->date]);
    }

    private function obtenerPrimeraTag($tags){
        $primeraTag = true;
        $tagEscogida = null;
        
        foreach($tags as $tag){
            if ($primeraTag){
                $tagEscogida = $tag;
                $primeraTag = false;
            }

            if ($tag->pivot->orden < $tagEscogida->pivot->orden){
                $tagEscogida = $tag;
            }
        }

        return $tagEscogida;
    }

    // : Para cambiar de mes en calendario. 
    public function cambiarMes($anterior){
        if ($anterior)
            $this->date = $this->date->subMonth();
        else
            $this->date = $this->date->addMonth();

        session(['fechaCalendario' => $this->date]);
        return redirect()->route('calendario.show');
    }

    // : Funciones para editar entradas desde el calendario. 
    public function cancelar(){
        $this->reset(['openEntrada']);
        $this->dispatchBrowserEvent('modal-cerrado');
    }

    public function verDetalles($idEntrada){
        $this->openEntrada = true;
        
        $this->entrada = Entrada::where('id', $idEntrada)->with('tags')->first();
        $this->contenido = $this->entrada->contenido;
        $this->tags = $this->entrada->tags;
        
        $this->dispatchBrowserEvent('modal-abierto', $idEntrada, $this->book);
    }

    public function update(){
        $this->validate([
            'entrada.contenido'=>['required', 'string', 'min:1']
        ]);

        $this->entrada->save();
    }
}
