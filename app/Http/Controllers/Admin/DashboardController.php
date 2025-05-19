<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
use App\Models\Reserva;
use App\Models\Horario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function index()
    {
        // Obtener estadísticas para el dashboard
        $stats = [
            'total_peliculas' => Pelicula::count(),
            'reservas_hoy' => Reserva::whereDate('created_at', Carbon::today())->count(),
            'ingresos_hoy' => Reserva::whereDate('created_at', Carbon::today())->count() * 8,
            'ocupacion_media' => $this->calcularOcupacionMedia(),
        ];

        // Obtener ingresos de los últimos 7 días
        $ingresosSemana = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::now()->subDays($i);
            $reservas = Reserva::whereDate('created_at', $fecha)->count();
            $ingresosSemana[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'dia' => $fecha->format('D'),
                'ingresos' => $reservas * 8
            ];
        }

        return view('admin.dashboard', compact('stats', 'ingresosSemana'));
    }

    private function calcularOcupacionMedia()
    {
        $horarios = Horario::with('reservas')
            ->whereDate('fecha', '>=', Carbon::now())
            ->get();

        if ($horarios->isEmpty()) {
            return 0;
        }

        $ocupacionTotal = 0;
        $capacidadSala = 100; // Asumimos una capacidad fija por sala

        foreach ($horarios as $horario) {
            $ocupacionTotal += ($horario->reservas->count() / $capacidadSala) * 100;
        }

        return round($ocupacionTotal / $horarios->count());
    }
} 