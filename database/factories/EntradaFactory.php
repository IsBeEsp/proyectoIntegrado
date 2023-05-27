<?php

namespace Database\Factories;

use App\Models\Tag;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrada>
 */
class EntradaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contenido'=>$this->faker->words(random_int(15, 25), true),
            'compleated_at'=> '',
        ];
    }
}

