<?php

namespace App\Http\Livewire;

use App\Models\Libro;
use Livewire\Component;

class ShowLibros extends Component
{
    public $campoOrden="created_at.asc";

    public $openEditar = false;
    public $nombre, $color;
    public Libro $libro;

    protected $listeners=[
        'render',
        'redirigir'
    ];

    protected $rules = [
        'libro.nombre'=>['required', 'string', 'min:3', 'unique:libros,nombre'],
        'libro.color'=>['required', 'string']
    ];

    public function mount(){
        session()->forget('idLibro');
        $this->libro = new Libro();
    }

    public function render()
    {
        $filtro = explode('.', $this->campoOrden);
        $libros = Libro::where('user_id', auth()->user()->id)->orderBy($filtro[0], $filtro[1])->get();
        
        $this->dispatchBrowserEvent('contenido-actualizado');

        return view('livewire.show-libros', compact('libros'));
    }

    //: Métodos para gestionar libros 

    public function editar(Libro $libro){
        $this->libro = $libro;
        $this->openEditar = true;
    }

    public function cancelar(){
        $this->reset(['openEditar']);
    }

    public function update(){
        $this->validate([
            'libro.nombre'=>['required', 'string', 'min:3', 'unique:libros,nombre,'.$this->libro->id]
        ]);

        $this->libro->save();
        $this->dispatchBrowserEvent('contenido-actualizado', ['id'=>$this->libro->id, 'color'=>$this->libro->color]);
        $this->cancelar();
        return redirect(request()->header('Referer'));
    }

    public function borrar(Libro $libro){
        $libro->delete();
    }

    // : Método para redirigir a página 
    public function redirigir($idLibro){
        session(['idLibro' => $idLibro]);
        session(['fechaPagina' => now()]);

        return redirect()->to('/page');
    }
}