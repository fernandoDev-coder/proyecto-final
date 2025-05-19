<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Auth::routes();

// Rutas públicas
Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
Route::get('/peliculas/{id}', [PeliculaController::class, 'show'])->name('peliculas.show');
Route::get('/catalogo', [PeliculaController::class, 'index'])->name('catalogo');

// Rutas para usuarios normales (autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/crear/{horario}', [ReservaController::class, 'createDesdeHorario'])->name('reservas.createDesdeHorario');
    Route::post('/reservas/store/{horario}', [ReservaController::class, 'storeDesdeHorario'])->name('reservas.storeDesdeHorario');
    Route::get('/reservas/{id}/qr', [ReservaController::class, 'mostrarQR'])->name('reservas.qr');
    Route::get('/reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::get('/peliculas/{pelicula}/horarios', [HorarioController::class, 'seleccionar'])->name('horarios.seleccionar');
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('usuario.perfil');
    Route::put('/perfil', [UsuarioController::class, 'actualizarPerfil'])->name('usuario.actualizar');
});

// Rutas de administración
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para películas
    Route::resource('peliculas', App\Http\Controllers\Admin\PeliculaController::class);
    Route::post('/peliculas/{id}/generar-horarios', [App\Http\Controllers\Admin\PeliculaController::class, 'generarHorarios'])->name('peliculas.generar-horarios');
    
    // Rutas para horarios
    Route::resource('horarios', App\Http\Controllers\Admin\HorarioController::class);
});

// Ruta de inicio después del login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
