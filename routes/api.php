<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\AsientoOcupado;
use App\Http\Controllers\ReservaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para obtener asientos ocupados (acceso público)
Route::get('/horarios/{horario}/asientos-ocupados', [ReservaController::class, 'getAsientosOcupados'])->withoutMiddleware(['auth:sanctum']); 