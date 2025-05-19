<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PeliculaController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
    }

    public function index()
    {
        $peliculas = Pelicula::orderBy('titulo', 'asc')->paginate(10);
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function create()
    {
        return view('admin.peliculas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|in:G,PG,PG-13,R,NC-17',
            'genero' => 'required|in:Acción,Aventura,Comedia,Drama,Ciencia ficción,Terror,Romance,Documental,Animación',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('peliculas', $nombreImagen, 'public');
            $validated['imagen'] = '/storage/peliculas/' . $nombreImagen;
        }

        Pelicula::create($validated);

        return redirect()
            ->route('admin.peliculas.index')
            ->with('success', 'Película creada correctamente');
    }

    public function edit(Pelicula $pelicula)
    {
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    public function update(Request $request, Pelicula $pelicula)
    {
        $validated = $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|in:G,PG,PG-13,R,NC-17',
            'genero' => 'required|in:Acción,Aventura,Comedia,Drama,Ciencia ficción,Terror,Romance,Documental,Animación',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($pelicula->imagen) {
                $oldPath = str_replace('/storage/', '', $pelicula->imagen);
                Storage::disk('public')->delete($oldPath);
            }
            
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->storeAs('peliculas', $nombreImagen, 'public');
            $validated['imagen'] = '/storage/peliculas/' . $nombreImagen;
        }

        $pelicula->update($validated);

        return redirect()
            ->route('admin.peliculas.index')
            ->with('success', 'Película actualizada correctamente');
    }

    public function destroy(Pelicula $pelicula)
    {
        // Eliminar la imagen asociada
        if ($pelicula->imagen) {
            $imagePath = str_replace('/storage/', '', $pelicula->imagen);
            Storage::disk('public')->delete($imagePath);
        }

        $pelicula->delete();

        return redirect()
            ->route('admin.peliculas.index')
            ->with('success', 'Película eliminada correctamente');
    }

    public function generarHorarios($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        
        return redirect()
            ->route('admin.horarios.create', ['pelicula_id' => $pelicula->id])
            ->with('info', 'Selecciona los horarios para la película ' . $pelicula->titulo);
    }
} 