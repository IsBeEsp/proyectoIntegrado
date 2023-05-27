<?php

namespace Database\Seeders;

use App\Models\Entrada;
use App\Models\Libro;
use App\Models\Pagina;
use App\Models\Tag;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntradaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtengo todos los libros y todas las páginas.
        $libros = Libro::all();
        $paginas = Pagina::all();

        // Creo una entrada para cada libro que asigno a todas sus páginas.
        foreach($libros as $libro){
            /*  Genero con 25% de probabilidades, una fecha que será la fecha actual, 
                y que asignaré a la entrada para mostrarla como completada. */ 
            $fechaComplecion = (random_int(1,4) == 4)  ? (new DateTime())->format('Y-m-d') : null;
            $entrada = Entrada::factory()->create(['compleated_at'=>$fechaComplecion]);

            // Asigno una cantidad aleatoria de tags a cada entrada
            for ($i = 0; $i<random_int(1,6); $i++){
                $tags = Tag::where('libro_id', $libro->id)->get()->toArray();
                $tag = Tag::where('libro_id', $libro->id)->inRandomOrder()->first();
                if (!in_array($tag, $tags))
                    $entrada->tags()->attach([$tag->id => ['orden' => $i]]);
            }

            // Asigno la entrada a todas las páginas.
            foreach($paginas as $pagina){
                if ($pagina->libro_id == $libro->id)
                    $entrada->paginas()->attach($pagina);
            }
        }
    }
}