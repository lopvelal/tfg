<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aula>
 */



class AulaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->words(2, true), // Genera una combinación de dos palabras
            'alias' => $this->faker->word(), // Genera una palabra
            'descripcion' => $this->faker->optional()->sentences(3, true), // Genera un texto opcional
            'plazas' => $this->faker->numberBetween(20, 40), // Genera un número entre 10 y 50
            'disponible' => $this->faker->boolean(), // Genera un valor booleano
        ];
    }
}
