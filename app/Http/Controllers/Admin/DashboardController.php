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
        $stats = [
            'total_peliculas' => Pelicula::count(),
            'reservas_hoy' => rand(80, 150),
            'ingresos_hoy' => rand(80, 150) * 8,
            'ocupacion_media' => rand(65, 85),
        ];

        $ingresosSemana = [];
        $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $reservas = rand(50, 200);
            $ingresosDia = $reservas * 8;
            
            if ($fecha->isWeekend()) {
                $ingresosDia *= 1.5;
            }
            
            $ingresosSemana[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'dia' => $diasSemana[$fecha->dayOfWeek],
                'ingresos' => round($ingresosDia, 2)
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
        $capacidadSala = 100;

        foreach ($horarios as $horario) {
            $ocupacionTotal += ($horario->reservas->count() / $capacidadSala) * 100;
        }

        return round($ocupacionTotal / $horarios->count());
    }
} 