<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PeliculaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Muestra todas las películas con paginación.
     */
    public function index(Request $request)
    {
        $query = Pelicula::query();
        
        // Búsqueda por título o descripción
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('titulo', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtro por género (uno solo)
        if ($request->filled('genero')) {
            $query->whereHas('generos', function($q) use ($request) {
                $q->where('generos.id', $request->genero);
            });
        }

        // Filtro por clasificación
        if ($request->filled('clasificacion') && $request->clasificacion !== 'todas') {
            $query->where('clasificacion', $request->clasificacion);
        }
        
        // Ordenar por título
        $query->orderBy('titulo', 'asc');
        
        // Obtener géneros únicos para el filtro
        $generos = \App\Models\Genero::orderBy('nombre')->get();
        
        // Paginar los resultados (12 películas por página)
        $peliculas = $query->with('generos')->paginate(12)->withQueryString();
        
        return view('peliculas.index', compact('peliculas', 'generos'));
    }

    /**
     * Muestra el formulario para crear una nueva película.
     */
    public function create()
    {
        return view('peliculas.create');
    }

    /**
     * Almacena una nueva película en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'genero' => 'required|string|max:255',
                'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'duracion' => 'required|integer|min:1',
                'clasificacion' => 'required|string|max:10'
            ]);

            // Procesar y guardar la imagen
            if ($request->hasFile('imagen')) {
                try {
                    $imagen = $request->file('imagen');
                    $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                    
                    // Guardar la imagen en el disco público
                    $path = $imagen->storeAs('peliculas', $nombreImagen, 'public');
                    
                    if (!$path) {
                        throw new \Exception('No se pudo guardar la imagen en el servidor.');
                    }
                    
                    $validated['imagen'] = '/storage/' . $path;
                    \Log::info('Ruta de imagen guardada: ' . $validated['imagen']);
                } catch (\Exception $e) {
                    \Log::error('Error al guardar la imagen: ' . $e->getMessage());
                    return redirect()
                        ->route('peliculas.create')
                        ->with('error', 'Error al guardar la imagen: ' . $e->getMessage())
                        ->withInput();
                }
            }

            $pelicula = Pelicula::create($validated);

            // Asociar el género a la película
            $genero = Genero::where('nombre', $validated['genero'])->first();
            if ($genero) {
                $pelicula->generos()->attach($genero->id);
            }

            if (!$pelicula) {
                throw new \Exception('No se pudo crear el registro de la película en la base de datos.');
            }

            // Generar horarios aleatorios para los próximos 7 días
            try {
                $salas = ['Sala 1', 'Sala 2', 'Sala 3'];
                $horas = ['15:00', '17:30', '20:00', '22:30'];
                
                for ($i = 0; $i < 7; $i++) {
                    $fecha = now()->addDays($i);
                    // Elegir 2 horarios aleatorios para cada día
                    $horariosDelDia = array_rand($horas, 2);
                    foreach ($horariosDelDia as $hora) {
                        \App\Models\Horario::create([
                            'pelicula_id' => $pelicula->id,
                            'fecha' => $fecha->format('Y-m-d'),
                            'hora' => $horas[$hora],
                            'sala' => $salas[array_rand($salas)]
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error al crear horarios: ' . $e->getMessage());
                // Continuamos aunque falle la creación de horarios
            }

            return redirect()->route('peliculas.index')
                ->with('success', '¡Película "' . $pelicula->titulo . '" agregada correctamente!');
        } catch (\Exception $e) {
            \Log::error('Error al crear película: ' . $e->getMessage());
            return redirect()
                ->route('peliculas.create')
                ->with('error', 'No se pudo añadir la película: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de una película específica.
     */
    public function show($id)
    {
        $pelicula = Pelicula::with(['horarios' => function($query) {
            $query->where('fecha', '>=', now())
                  ->orderBy('fecha', 'asc')
                  ->orderBy('hora', 'asc');
        }])->findOrFail($id);

        return view('peliculas.show', compact('pelicula'));
    }

    /**
     * Muestra el formulario para editar una película existente.
     */
    public function edit($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        return view('peliculas.edit', compact('pelicula'));
    }

    /**
     * Actualiza los datos de una película.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'genero' => 'required|string|max:255',
            'imagen' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|string|max:10'
        ]);

        $pelicula = Pelicula::findOrFail($id);
        $pelicula->update($validated);

        return redirect()
            ->route('peliculas.show', $pelicula)
            ->with('success', 'Película actualizada correctamente.');
    }

    /**
     * Elimina una película de la base de datos.
     */
    public function destroy($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        
        // Eliminar la imagen si existe
        if ($pelicula->imagen) {
            $rutaImagen = str_replace('storage/', 'public/', $pelicula->imagen);
            Storage::delete($rutaImagen);
        }
        
        $pelicula->delete();

        return redirect()
            ->route('peliculas.index')
            ->with('success', 'Película eliminada correctamente.');
    }

    public function adminIndex()
    {
        $peliculas = Pelicula::orderBy('titulo', 'asc')->paginate(10);
        return view('admin.peliculas.index', compact('peliculas'));
    }
}
