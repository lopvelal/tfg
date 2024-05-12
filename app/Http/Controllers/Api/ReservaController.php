<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\StoreRequest;
use App\Http\Requests\Reserva\UpdateRequest;
use App\Models\Aula;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reserva::withCount(['reservasUsuarios as plazas_ocupadas'])
            ->with('aula')
            ->orderBy('fecha')
            ->paginate(5);
    }
    /**
     * Devuelve las reservas hechas para un aula y fecha indicadas.
     */
    public function getReservasAulaFecha($idAula, $fechaReserva)
    {
        $reservas = Aula::find($idAula)->reservas()->withCount('reservasUsuarios as plazas_ocupadas')->whereDate('fecha', '=', $fechaReserva)->get();
        return $reservas;
    }

    /**
     * Devuelve los usuarios apuntados a unas reserva
     */
    public function getUsuariosReserva($idReserva)
    {
        return Reserva::findOrFail($idReserva)->reservasUsuarios()->with('user')->orderBy('orden', 'asc')->paginate(5);
    }


    public function obtenerEspaciosDisponibles($fecha, $aula_id)
    {
        $horariosOcupados = Reserva::whereDate('fecha', $fecha)->where('aula_id', $aula_id)->get(['hora_inicio', 'duracion']);

        $espaciosDisponibles = $this->calcularEspaciosDisponibles($fecha, $horariosOcupados);

        return $espaciosDisponibles;
    }

    protected function calcularEspaciosDisponibles($fecha, $horariosOcupados)
    {
        $inicioDelDia = Carbon::createFromFormat('Y-m-d H:i:s', $fecha . ' 08:00:00');

        // Define los bloques horarios de una hora desde las 08:00 hasta las 21:00
        $horarios = collect();
        for ($hour = 0; $hour <= 13; $hour++) { // 21 - 8 = 13 horas
            $horarioActual = $inicioDelDia->copy()->addHours($hour);
            $horarios->push($horarioActual->format('H:i:s'));
        }

        // Elimina los horarios ocupados
        foreach ($horariosOcupados as $reserva) {
            $inicio = Carbon::createFromTimeString($reserva->hora_inicio);
            for ($i = 0; $i < $reserva->duracion; $i++) {
                $horaAEliminar = $inicio->copy()->addHours($i)->format('H:i:s');
                $horarios = $horarios->reject(function ($hora) use ($horaAEliminar) {
                    return $hora === $horaAEliminar;
                });
            }
        }

        return $horarios;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return Reserva::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        $reserva = Reserva::withCount('reservasUsuarios as plazas_ocupadas')
            ->with('aula')
            ->findOrFail($reserva->id);
        return $reserva;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Reserva $reserva)
    {
        $data = $request->validated();
        $reserva->update($data);
        return $reserva;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return \response()->json(['mensaje' => 'Se ha borrado correctamente']);
    }
}
