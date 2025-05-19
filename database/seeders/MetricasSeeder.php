<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelicula;
use App\Models\Horario;
use App\Models\Reserva;
use Carbon\Carbon;

class MetricasSeeder extends Seeder
{
    public function run()
    {
        // Obtener todas las películas existentes
        $peliculas = Pelicula::all();
        
        foreach ($peliculas as $pelicula) {
            // Crear horarios para los últimos 30 días
            for ($i = 30; $i >= 0; $i--) {
                $fecha = Carbon::now()->subDays($i);
                
                // Crear 3 horarios por día
                for ($sala = 1; $sala <= 3; $sala++) {
                    $horario = Horario::create([
                        'pelicula_id' => $pelicula->id,
                        'fecha' => $fecha->format('Y-m-d'),
                        'hora' => ['14:00', '17:00', '20:00'][rand(0, 2)],
                        'sala' => $sala,
                        'precio' => rand(8, 15) * 100 // Precio entre 800 y 1500
                    ]);

                    // Crear entre 5 y 20 reservas por horario
                    $numReservas = rand(5, 20);
                    for ($k = 0; $k < $numReservas; $k++) {
                        $cantidadAsientos = rand(1, 4);
                        Reserva::create([
                            'horario_id' => $horario->id,
                            'user_id' => 1, // Usuario de prueba
                            'cantidad_asientos' => $cantidadAsientos,
                            'precio_total' => $horario->precio * $cantidadAsientos,
                            'created_at' => $fecha->copy()->addHours(rand(-12, 12)),
                            'estado' => 'completada'
                        ]);
                    }
                }
            }
        }
    }
} 