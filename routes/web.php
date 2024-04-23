<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('app'); // Asegúrate de reemplazar 'tuVistaPrincipal' con el nombre de tu vista principal de Vue.
});

Route::get('/{any}', function () {
    return view('app'); // Asegúrate de reemplazar 'tuVistaPrincipal' con el nombre de tu vista principal de Vue.
})->where('any', '.*');

require __DIR__ . '/auth.php';
