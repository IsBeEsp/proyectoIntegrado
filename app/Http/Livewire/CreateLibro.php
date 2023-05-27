<?php

namespace App\Http\Livewire;

use App\Models\Libro;
use Livewire\Component;

class CreateLibro extends Component
{
    public bool $openCrear = false;
    public $nombre, $color;

    protected $rules = [
        'nombre'=>['required', 'string', 'min:3', 'unique:libros,nombre'],
        'color'=>['required', 'string']
    ];

    public function render()
    {
        return view('livewire.create-libro');
    }

    public function crear(){
        $this->validate();
        Libro::create([
            'nombre'=>$this->nombre,
            'color'=>$this->color,
            'user_id'=>auth()->user()->id
        ]);

        $this->emitTo('show-libros', 'render');
        $this->reset(['openCrear', 'nombre', 'color']);
    }
}
