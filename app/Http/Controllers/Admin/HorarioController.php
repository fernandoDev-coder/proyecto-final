<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Pelicula;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function index()
    {
        $peliculas = Pelicula::with(['horarios' => function($query) {
            $query->orderBy('fecha', 'asc')
                  ->orderBy('hora', 'asc');
        }])
        ->whereHas('horarios')
        ->orderBy('titulo', 'asc')
        ->get();
        
        return view('admin.horarios.index', compact('peliculas'));
    }

    public function create(Request $request)
    {
        $peliculaSeleccionada = null;
        if ($request->has('pelicula_id')) {
            $peliculaSeleccionada = Pelicula::findOrFail($request->pelicula_id);
        }
        $peliculas = Pelicula::orderBy('titulo')->get();
        return view('admin.horarios.create', compact('peliculas', 'peliculaSeleccionada'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pelicula_id' => 'required|exists:peliculas,id',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required',
                'sala' => 'required|string'
            ]);

            $pelicula = Pelicula::find($validated['pelicula_id']);
            Horario::create($validated);

            return redirect()
                ->route('admin.horarios.index')
                ->with('success', 'Horario creado correctamente para la película "' . $pelicula->titulo . '"');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el horario: ' . $e->getMessage());
        }
    }

    public function edit(Horario $horario)
    {
        $peliculas = Pelicula::orderBy('titulo')->get();
        return view('admin.horarios.edit', compact('horario', 'peliculas'));
    }

    public function update(Request $request, Horario $horario)
    {
        $validated = $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'sala' => 'required|string'
        ]);

        $horario->update($validated);

        return redirect()
            ->route('admin.horarios.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()
            ->route('admin.horarios.index')
            ->with('success', 'Horario eliminado correctamente');
    }

    public function generarHorariosAleatorios($peliculaId)
    {
        try {
            $pelicula = Pelicula::findOrFail($peliculaId);
            
            // Generar entre 2 y 4 salas aleatorias
            $numSalas = rand(2, 4);
            $salas = [];
            for ($i = 1; $i <= $numSalas; $i++) {
                $salas[] = 'Sala ' . $i;
            }
            
            $horas = ['15:00', '17:30', '20:00', '22:30'];
            
            // Generar horarios para los próximos 7 días
            for ($i = 0; $i < 7; $i++) {
                $fecha = now()->addDays($i);
                // Elegir 2 horarios aleatorios para cada sala
                foreach ($salas as $sala) {
                    $horariosDelDia = array_rand($horas, 2);
                    foreach ($horariosDelDia as $hora) {
                        Horario::create([
                            'pelicula_id' => $pelicula->id,
                            'fecha' => $fecha->format('Y-m-d'),
                            'hora' => $horas[$hora],
                            'sala' => $sala
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Horarios generados correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar los horarios: ' . $e->getMessage()
            ], 500);
        }
    }
} 