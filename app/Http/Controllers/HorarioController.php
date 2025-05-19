<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Pelicula;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class)
            ->except(['seleccionar']);
    }

    public function index()
    {
        $horarios = Horario::with('pelicula')->get(); // Carga relacionada
        return view('horarios.index', compact('horarios'));
    }

    public function create()
    {
        $peliculas = Pelicula::all();
        return view('horarios.create', compact('peliculas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'sala' => 'required|string|max:255',
        ]);

        Horario::create($validated);
        return redirect()->route('horarios.index')->with('success', 'Horario creado correctamente.');
    }

    public function show($id)
    {
        $horario = Horario::with('pelicula')->findOrFail($id);
        return view('horarios.show', compact('horario'));
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $peliculas = Pelicula::all();
        return view('horarios.edit', compact('horario', 'peliculas'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'sala' => 'required|string|max:255',
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update($validated);
        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado.');
    }

    /**
     * Muestra los horarios disponibles para una película (pantalla de selección).
     */
    public function seleccionar($id)
    {
        $pelicula = Pelicula::with('horarios')->findOrFail($id);

        // Si no hay horarios en la BD, generar 5 aleatorios solo para visualizar (no guardar)
        if ($pelicula->horarios->isEmpty()) {
            $horariosAleatorios = collect();

            for ($i = 0; $i < 5; $i++) {
                $horariosAleatorios->push((object) [
                    'fecha' => now()->addDays(rand(1, 7))->format('Y-m-d'),
                    'hora' => sprintf('%02d:00', rand(15, 22)),
                    'sala' => 'Sala ' . rand(1, 3),
                    'id' => 0 // No seleccionable
                ]);
            }

            $pelicula->setRelation('horarios', $horariosAleatorios);
        }

        return view('horarios.seleccionar', compact('pelicula'));
    }
}
