<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Entrada as ModeloEntrada;

class Entrada extends Component
{
    public $entrada;
    public $idEntrada;
    public $contenido;
    public $tags;
    public $compleated_at;

    protected  $listeners = [
        'refrescar-entrada' => '$refresh',
    ];

    public function mount($entrada=null){
        if ($entrada != null){
            $this->idEntrada = $entrada->id;
            $this->contenido = $entrada->contenido;
            $this->compleated_at = $entrada->compleated_at;
        }
    }

    public function render()
    {
        if ($this->entrada != null)
            $this->tags = $this->entrada->tags()->orderBy('entrada_tag.orden')->get();
        
        return view('livewire.entrada');
    }
}
