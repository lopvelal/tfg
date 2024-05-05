<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReservasUsuario;
use Illuminate\Http\Request;

class ReservaUsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReservasUsuario::paginate(10);
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
}
