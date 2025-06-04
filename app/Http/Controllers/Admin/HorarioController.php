<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Pelicula;
use App\Models\AsientoOcupado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $horarios = Horario::with('pelicula')->orderBy('fecha', 'desc')->paginate(10);
        $peliculas = Pelicula::with('horarios')->get();
        return view('admin.horarios.index', compact('horarios', 'peliculas'));
    }

    public function create()
    {
        $peliculas = Pelicula::all();
        $peliculaSeleccionada = null;
        
        if (request()->has('pelicula_id')) {
            $peliculaSeleccionada = Pelicula::findOrFail(request('pelicula_id'));
        }
        
        return view('admin.horarios.create', compact('peliculas', 'peliculaSeleccionada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'sala' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['sala'] = 'Sala ' . $data['sala'];
            $data['precio'] = 8.00; // Precio fijo

            $horario = Horario::create($data);

            DB::commit();

            return redirect()->route('admin.horarios.index')
                ->with('success', 'Horario creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear horario: ' . $e->getMessage());
            return back()->with('error', 'Error al crear el horario: ' . $e->getMessage());
        }
    }

    public function edit(Horario $horario)
    {
        $peliculas = Pelicula::all();
        return view('admin.horarios.edit', compact('horario', 'peliculas'));
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'sala' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0'
        ]);

        $horario->update($request->all());

        return redirect()->route('admin.horarios.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('admin.horarios.index')
            ->with('success', 'Horario eliminado correctamente');
    }
} 