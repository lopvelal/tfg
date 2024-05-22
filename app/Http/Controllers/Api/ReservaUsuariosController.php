<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservasUsuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaUsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReservasUsuario::paginate(10);
    }

    public function alumnoInscrito($reserva_id)
    {
        $user_id = Auth::id();
        $data = ReservasUsuario::where('user_id', $user_id)->where('reserva_id', $reserva_id)->first();

        return \response()->json(['status' => $data ? 'ok' : 'ko']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'usuario' => 'required|integer|exists:users,id', // Validar que el usuario existe
            'reserva' => 'required|integer|exists:reservas,id', // Validar que la reserva existe
        ]);

        // Obtener el user_id y reserva_id del request
        $user_id = $validatedData['usuario'];
        $reserva_id = $validatedData['reserva'];

        // Obtener el máximo orden para la reserva_id proporcionada
        $maxOrden = ReservasUsuario::where('reserva_id', $reserva_id)->max('orden');

        // Si no hay registros, el orden será 1
        $orden = $maxOrden ? $maxOrden + 1 : 1;

        // Crear el nuevo registro
        $nuevoRegistro = new ReservasUsuario();
        $nuevoRegistro->user_id = $user_id;
        $nuevoRegistro->reserva_id = $reserva_id;
        $nuevoRegistro->orden = $orden;

        // Comprobar si quedan plazas disponibles en el aula
        $plazasOcupadas = ReservasUsuario::where('reserva_id', $reserva_id)->count();
        $plazasDisponibles = $nuevoRegistro->reserva->aula->plazas - $plazasOcupadas;

        if ($plazasDisponibles <= 0) {
            return \response()->json(['status' => 'ko', 'mensaje' => 'No quedan plazas disponibles']);
        } else {
            try {
                $nuevoRegistro->save();
                return \response()->json(['status' => 'ok']);
            } catch (QueryException $e) {
                return \response()->json(['status' => 'ko', 'mensaje' => 'Usuario ya inscrito']);
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ReservasUsuario $reservasUsuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservasUsuario $reservasUsuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservasUsuario $reservasUsuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservasUsuario $reservasUsuario)
    {
        return response()->json($reservasUsuario->delete() ? 'true' : 'false');
    }

    public function desinscribirAlumno($reserva_id)
    {
        $user_id = Auth::id();
        $result = ReservasUsuario::where('reserva_id', $reserva_id)->where('user_id', $user_id)->first();
        $result = $result->delete();
        return response()->json(['status' => $result ? 'ok' : 'ko']);
    }
}
