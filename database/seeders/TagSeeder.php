<?php

namespace Database\Seeders;

use App\Models\Libro;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtengo todos los libros
        $libros = Libro::all();
        
        // Genero 12 tags para cada libro.
        foreach($libros as $libro){
            for ($i = 0; $i<12; $i++){
                
                // Genero el color para cada tag.
                $valores = [];
                for($j = 0; $j<3; $j++){
                    $valor = dechex(random_int(0, 255));
                    $valor = (strlen($valor) < 2) ? "0".$valor : $valor;
                    $valores[$j] = $valor;
                }
                $color = '#'.implode($valores);
                
                // Creo la tag.
                Tag::factory()->create(['color'=>$color,'libro_id'=>$libro->id]);
            }
        }
    }
}
