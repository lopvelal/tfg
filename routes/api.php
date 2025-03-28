<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Api\AulaController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\ReservaUsuariosController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/aulas/disponibles', [AulaController::class, 'getAulasDisponibles']);
    Route::resource('/aulas', AulaController::class)->except(['create']);

    Route::get('/obtener-espacios-disponibles/{fecha}/{aula_id}', [ReservaController::class, 'obtenerEspaciosDisponibles']);
    Route::get('/reservas/usuarios/{idReserva}', [ReservaController::class, 'getUsuariosReserva']);
    Route::get('/reservas/{idAula}/{fecha}', [ReservaController::class, 'getReservasAulaFecha']);
    Route::get('/reservas/profesor', [ReservaController::class, 'getReservasProfesor']);
    Route::get('/reservas/alumno', [ReservaController::class, 'getReservasAlumno']);
    Route::resource('/reservas', ReservaController::class)->except(['create']);


    Route::get('/reservas-usuarios/alumno-inscrito/{reserva_id}', [ReservaUsuariosController::class, 'alumnoInscrito']);
    Route::delete('/reservas-usuarios/baja/{reserva_id}', [ReservaUsuariosController::class, 'desinscribirAlumno']);
    Route::resource('/reservas-usuarios', ReservaUsuariosController::class)->except(['create']);

    Route::get('/user/permissions', [PermissionController::class, 'getUserPermissions']);
    Route::put('/user/changePassword', [ChangePasswordController::class, 'changePassword']);
});
