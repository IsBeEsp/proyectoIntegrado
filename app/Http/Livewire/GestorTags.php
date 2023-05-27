<?php

namespace App\Http\Livewire;

use App\Models\Entrada;
use App\Models\Tag;
use Livewire\Component;

class GestorTags extends Component
{
    public $openTags = false;

    public $libroId;
    public $idEntrada;
    public $entrada;
    public $tags;

    protected $listeners = [
        'refrescar' => '$refresh',
        'toggleGestor',
        'ordenarTags',
        'cambiarTag',
        'crearTag',
        'actualizarTag',
        'borrarTag'
    ];

    public function mount($libroId, $tags)
    {
        $this->libroId = $libroId;
        $this->tags = $tags;
    }

    public function render()
    {
        $tagsEntrada = [];
        $tagsRestantes = [];

        // Obtengo las tags correspondientes a una entrada y las restantes.
        if ($this->idEntrada != null) {

            // Obtengo la lista completa de tags desde el componente página; alternativamente, realizo una consulta.
            // La consulta alternativa es para la vista calendario.
            if ($this->tags != null)
                $tags = $this->tags;
            else {
                $tags = Tag::where('libro_id', $this->libroId)->get();
                $this->tags = $tags;
            }

            // Obtengo cada entrada con todas sus tags ordenadas por el campo de orden de la tabla auxiliar.
            $this->entrada = Entrada::where('id', $this->idEntrada)->with('tags')->first();
            $tagsEntrada = $this->entrada->tags()->orderBy('entrada_tag.orden')->get();

            // Separo las tags en dos listas: las pertenecintas a la entrada, y el resto.
            $tagsRestantes = $tags->diff($tagsEntrada);

            // Ordeno las tags que no pertenecen a entrada por campos de orden (si el campo es null el orden es PHP_INT_MAX, es decir, se va al final).
            $tagsRestantes = $tagsRestantes->sortBy(function ($tag) {
                return $tag->orden ?? PHP_INT_MAX;
            });
        }

        return view('livewire.gestor-tags', compact('tagsEntrada', 'tagsRestantes'));
    }

    // : Función para mostrar/ocultar gestor de tags.

    public function toggleGestor($toggle, $idEntrada = null)
    {
        $this->openTags = $toggle;
        if ($toggle){
            $this->idEntrada = $idEntrada;
            $this->dispatchBrowserEvent('gestorAbierto');
        } else 
            return redirect()->back();
    }

    // : Función para ordenar las tags en sus listas.
    // Re-establezco el orden de las tags si estas han sido alteradas.
    // Si entrada == true significa que estamos reordenando las tags dentro de una entrada, por lo que cambia su orden en la relación tag-entrada.

    // ! NOTA MUY IMPORTANTE: Sería mucho más eficiente si la lista de tags fuese de tipo 'swap' en vez de 'drag and drop', porque las posiciones de las tags movidas se intercambiarían
    // ! realizando sólo 2 consultas por reordenación. No como actualmente que mover una tag mueve a todas hacia arriba/abajo dependiendo de dónde este se haya relocalizado.
    // ! Esto es muy fácil de cambiar y de hecho ya lo hago en la lista de tags resumida que aparece sobre las entradas, sin embargo, por ahora lo dejo como está por motivos
    // ! estéticos.
    public function ordenarTags($listaIdTags, $entrada = false)
    {
        // + Para reorganizar tags dentro de la lista general de tags.
        // * Si entrada es igual a 'true' ordenaremos las tags por su campo 'orden'.
        // * De otro modo, ordenaremos las tags por el campo de orden en la tabla axiliar con la correspondiente entrada.
        if (!$entrada) {
            // - Mapeo las tags a un array con su id como clave.
            $tagsPorID =  [];
            foreach ($this->tags as $tag) {
                $tagsPorID[$tag->id] = $tag;
            }

            // - Obtengo cada tag fácilmente sin tener que acceder a las propiedades del objeto con el mapa recién creado.
            // / Modifico el campo orden con la clave del array de tags recibido desde el front-end.
            // / Con el método update(). Sólo se realizará una consulta SQL para aquellas tags cuyo orden haya cambiado, siendo así mucho más eficiente.
            foreach ($listaIdTags as $k => $idTag) {
                $tagsPorID[$idTag]->orden = $k;
                $tagsPorID[$idTag]->update();
            }
        } else {
            $this->entrada->tags()->syncWithoutDetaching(
                collect($listaIdTags)->mapWithKeys(function ($idTag, $orden){
                    return [$idTag => ['orden' => $orden]];
                })
            );
        }
    }

    // : Funcion para añadir o quitar tags de una entrada. 

    public function cambiarTag($tagId, $añadir, $idEntrada = null)
    {
        if ($idEntrada != null)
            $entrada = Entrada::where('id', $idEntrada)->with('tags')->first();
        else
            $entrada = $this->entrada;

        $tagCambiada = null;

        // Busco la tag en la lista de tags que ya obtuve en render() para avitar hacer queries adicionales a la BD.
        foreach ($this->tags as $tag) {
            if ($tag->id == $tagId)
                $tagCambiada = $tag;
        }

        if ($añadir)
            $entrada->tags()->attach($tagCambiada);
        else
            $entrada->tags()->detach($tagCambiada);

        if ($idEntrada != null)
            return redirect()->back()->with('book', $this->libroId);
    }

    // : Funcion para crear tags. 

    public function crearTag($nombreTag, $colorTag, $añadirEntrada)
    {
        $tag = Tag::create(['nombre' => $nombreTag, 'color' => $colorTag, 'libro_id' => $this->libroId]);
        $this->tags[] = $tag;

        if ($añadirEntrada) {
            $this->entrada->tags()->attach($tag);
            $this->emit('refrescar');
        }

        $this->dispatchBrowserEvent('actualizarCheckbox');
    }

    // : Funcion para editar tags. 

    public function actualizarTag($idTag, $campo, $valor)
    {
        // Obtengo la tag de la lista con la id correspondiente; edito el campo y lo actualizo en la BD.
        foreach ($this->tags as $tag) {
            if ($tag->id == $idTag) {
                if ($campo == "nombre")
                    $tag->nombre = $valor;
                else if ($campo == "color")
                    $tag->color = $valor;

                $tag->update();
                return;
            }
        }
    }

    // : Función para eliminar tags. 

    public function borrarTag($idTag)
    {
        foreach ($this->tags as $tag) {
            if ($tag->id == $idTag) {
                $tag->delete();
                $this->emit('refrescar');
                return;
            }
        }
    }
}
