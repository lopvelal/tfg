<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reserva\StoreRequest;
use App\Http\Requests\Reserva\UpdateRequest;
use App\Models\Aula;
use App\Models\Reserva;
use App\Models\ReservasUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validación de entrada
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'sort_by' => 'nullable|string|in:titulo,fecha,aula_id', // Añadir campos por los que se puede ordenar
            'order' => 'nullable|string|in:asc,desc' // Orden ascendente o descendente
        ]);

        // Construir la consulta
        $query = Reserva::withCount(['reservasUsuarios as plazas_ocupadas'])
            ->with('aula');

        // Aplicar ordenación
        $sortBy = $validated['sort_by'] ?? 'fecha'; // Ordenar por 'fecha' por defecto
        $order = $validated['order'] ?? 'asc'; // Orden ascendente por defecto
        $query->orderBy($sortBy, $order);

        // Filtrado basado en la búsqueda
        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('fecha', 'like', "%{$search}%")
                    ->orWhereHas('aula', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                        $q->orWhere('alias', 'like', "%{$search}%");
                    });
            });
        }

        // Ejecutar la consulta con paginación
        $perPage = $validated['per_page'] ?? 5;
        $reservas = $query->paginate($perPage);

        // Devolver la respuesta en formato JSON
        return response()->json($reservas);
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
     * Devuelve las reservas de un profesor
     */
    public function getReservasProfesor()
    {
        return Reserva::withCount(['reservasUsuarios as plazas_ocupadas'])
            ->with('aula')
            ->where('user_id', Auth::id())
            ->whereDate('fecha', '>=', date('Y-m-d'))
            ->orderBy('fecha')
            ->paginate(5);
    }
    /**
     * Devuelve las reservas a las que está dado de alta un alumno
     */
    public function getReservasAlumno()
    {
        $user_id = Auth::id();

        $reservas_usuarios = ReservasUsuario::where('user_id', $user_id)->pluck('reserva_id');

        return Reserva::withCount(['reservasUsuarios as plazas_ocupadas'])
            ->with('aula')
            ->whereIn('id', $reservas_usuarios)
            ->whereDate('fecha', '>=', date('Y-m-d'))
            ->orderBy('fecha')
            ->paginate(5);
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
        //Comprobar si el aula está ocupada
        $reservas = Reserva::where('aula_id', $request->aula_id)
            ->where('fecha', $request->fecha)
            ->where('hora_inicio', $request->hora_inicio)
            ->get();
        if ($reservas->count() > 0) {
            return response()->json(['status' => 'ko', 'mensaje' => 'El aula ya está reservada en esa fecha y hora'], 409);
        }
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
        return \response()->json(['status' => 'ok', 'mensaje' => 'Se ha borrado correctamente']);
    }
}
