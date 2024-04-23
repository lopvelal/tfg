<?php

namespace Database\Factories;

use App\Models\Aula;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(), // Genera una oración como título
            'descripcion' => $this->faker->optional()->paragraph(), // Genera un párrafo opcional como descripción
            'fecha_reserva' => $this->faker->dateTimeBetween('+1 week', '+1 month'), // Genera una fecha entre la próxima semana y el próximo mes
            'user_id' => User::inRandomOrder()->first()->id, // Asigna un user_id vinculando un User generado por su factory
            'aula_id' => Aula::inRandomOrder()->first()->id // Asigna un aula_id vinculando un Aula generado por su factory
        ];
    }
}
