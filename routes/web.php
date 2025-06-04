<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Auth::routes();

// Rutas públicas
Route::get('/peliculas', [PeliculaController::class, 'index'])->name('peliculas.index');
Route::get('/peliculas/{id}', [PeliculaController::class, 'show'])->name('peliculas.show');

// Rutas para usuarios normales (autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/crear/{pelicula}', [ReservaController::class, 'create'])->name('reservas.create');
    Route::get('/reservas/crear-desde-horario/{horario}', [ReservaController::class, 'createDesdeHorario'])->name('reservas.createDesdeHorario');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::get('/reservas/{id}/qr', [ReservaController::class, 'mostrarQR'])->name('reservas.mostrarQR');
    Route::get('/peliculas/{pelicula}/horarios', [HorarioController::class, 'seleccionar'])->name('horarios.seleccionar');
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('usuario.perfil');
    Route::put('/perfil', [UsuarioController::class, 'actualizarPerfil'])->name('usuario.actualizar');
});

// Rutas de administración
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas para películas
    Route::resource('peliculas', App\Http\Controllers\Admin\PeliculaController::class);
    
    // Rutas para horarios
    Route::resource('horarios', App\Http\Controllers\Admin\HorarioController::class);
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
