<?php

namespace Database\Seeders;

use App\Models\Libro;
use App\Models\Pagina;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaginaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libros = Libro::all();
        
        foreach($libros as $libro){
            // Crea página para la fecha de hoy.
            $fechaActual = new DateTime();
            Pagina::factory()->create(['libro_id'=>$libro->id, 'created_at'=>$fechaActual]);    
            
            // Crea página para los 7-14 días anteriores.
            for ($i = 0; $i<random_int(7, 14); $i++){
                $fechaActual->sub(new DateInterval('P1D'));
                Pagina::factory()->create(['libro_id'=>$libro->id, 'created_at'=>$fechaActual]);    
            }
        }
    }
}