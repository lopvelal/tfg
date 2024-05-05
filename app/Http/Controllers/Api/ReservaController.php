<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reserva::withCount(['reservasUsuarios'])->paginate(5);
    }
    /**
     * Devuelve las reservas hechas para un aula y fecha indicadas.
     */
    public function getReservasAulaFecha($idAula, $fechaReserva)
    {
        $reservas = Aula::find($idAula)->reservas()->withCount('reservasUsuarios as plazas_ocupadas')->whereDate('fecha_reserva', '=', $fechaReserva)->get();
        return $reservas;
    }

    /**
     * Devuelve los usuarios apuntados a unas reserva
     */
    public function getUsuariosReserva($idReserva)
    {
        return Reserva::findOrFail($idReserva)->reservasUsuarios()->with('user')->orderBy('orden', 'asc')->paginate(5);
    }


    public function deleteUsuarioReserva($idRservaUsuario){

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        //
    }
}
