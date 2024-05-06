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
        $fecha = $this->faker->dateTimeBetween('+1 week', '+1 month');
        $hora_inicio = $this->faker->numberBetween(8, 20). ':00:00';

        return [
            'titulo' => $this->faker->sentence(),
            'descripcion' => $this->faker->paragraph(),
            'fecha' => $fecha->format('Y-m-d'),
            'hora_inicio' => $hora_inicio,
            'duracion' => $this->faker->numberBetween(1, 2),
            'user_id' => User::role('profesor')->inRandomOrder()->first()->id, // Asigna un user_id vinculando un User generado por su factory
            'aula_id' => Aula::inRandomOrder()->first()->id // Asigna un aula_id vinculando un Aula generado por su factory
        ];
    }
}
