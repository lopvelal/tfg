<?php

namespace Database\Factories;

use App\Models\Aula;
use App\Models\Reserva;
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
        $aula = Aula::inRandomOrder()->first();

        do {
            $fecha = $this->faker->dateTimeBetween('+1 week', '+1 month');
            $hora_inicio = $this->faker->numberBetween(8, 20) . ':00:00';
            $duracion = $this->faker->numberBetween(1, 2);

            $existeReserva = Reserva::where('aula_id', $aula->id)
                ->where('fecha', $fecha->format('Y-m-d'))
                ->where('hora_inicio', '<=', $hora_inicio)
                ->whereRaw('ADDTIME(hora_inicio, SEC_TO_TIME(duracion * 3600)) > ?', [$hora_inicio])
                ->exists();
        } while ($existeReserva);

        $titulo = $this->faker->words(3, true);

        return [
            'titulo' => $titulo,
            'descripcion' => $this->faker->paragraph(),
            'fecha' => $fecha->format('Y-m-d'),
            'hora_inicio' => $hora_inicio,
            'duracion' => $duracion,
            'user_id' => User::role('profesor')->inRandomOrder()->first()->id,
            'aula_id' => $aula->id
        ];
    }
}
