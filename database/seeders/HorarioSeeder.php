<?php
namespace Database\Seeders;

use App\Models\Horario;
use App\Models\Pelicula;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HorarioSeeder extends Seeder
{
    public function run()
    {
        // Obtener todas las películas
        $peliculas = Pelicula::all();

        // Crear horarios para cada película
        foreach ($peliculas as $pelicula) {
            // Generar 5 fechas aleatorias en junio 2025
            $fechas = collect([]);
            $fecha = Carbon::create(2025, 6, 1);
            $ultimoDiaJunio = Carbon::create(2025, 6, 30);

            while ($fechas->count() < 5) {
                $fechaPropuesta = $fecha->copy()->addDays(rand(0, 29));
                if ($fechaPropuesta->lte($ultimoDiaJunio) && !$fechas->contains($fechaPropuesta->format('Y-m-d'))) {
                    $fechas->push($fechaPropuesta->format('Y-m-d'));
                }
            }

            // Ordenar las fechas
            $fechas = $fechas->sort();

            // Crear horarios para cada fecha
            foreach ($fechas as $fecha) {
                Horario::create([
                    'pelicula_id' => $pelicula->id,
                    'fecha' => $fecha,
                    'hora' => $this->obtenerHoraAleatoria(),
                    'sala' => 'Sala ' . rand(1, 10),
                ]);
            }
        }
    }

    private function obtenerHoraAleatoria()
    {
        $horas = ['15:30', '16:00', '17:30', '18:00', '19:30', '20:00', '21:30', '22:00'];
        return $horas[array_rand($horas)];
    }
}
