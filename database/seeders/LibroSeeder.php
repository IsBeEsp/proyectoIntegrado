<?php

namespace Database\Seeders;

use App\Models\Libro;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtengo todos los usuarios
        $users = User::all();
        
        // Genero 3 libros para cada usuario.
        foreach($users as $user){
            for ($i = 0; $i<3; $i++){
                
                // Genero el color para cada libro.
                $valores = [];
                for($j = 0; $j<3; $j++){
                    $valor = dechex(random_int(0, 255));
                    $valor = (strlen($valor) < 2) ? "0".$valor : $valor;
                    $valores[$j] = $valor;
                }
                $color = '#'.implode($valores);
                
                // Creo el libro.
                Libro::factory()->create(['color'=>$color,'user_id'=>$user->id]);
            }
        }
    }
}
