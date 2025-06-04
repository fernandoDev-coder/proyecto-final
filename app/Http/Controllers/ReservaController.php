<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Pelicula;
use App\Models\User;
use App\Models\AsientoOcupado;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Services\ReservaService;

class ReservaController extends Controller
{
    protected $reservaService;

    public function __construct(ReservaService $reservaService)
    {
        $this->middleware('auth')->except(['getAsientosOcupados']);
        $this->middleware(function ($request, $next) {
            if (auth()->user() && auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Los administradores no pueden realizar reservas');
            }
            return $next($request);
        })->except(['getAsientosOcupados']);
        $this->reservaService = $reservaService;
    }

    public function index()
    {
        $reservas = auth()->user()->reservas()->with(['pelicula', 'horario'])->latest()->get();
        return view('reservas.index', compact('reservas'));
    }

    public function create(Pelicula $pelicula)
    {
        $horarios = $pelicula->horarios()
            ->where('fecha', '>=', now()->format('Y-m-d'))
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('reservas.create', compact('pelicula', 'horarios'));
    }

    public function createDesdeHorario(Horario $horario)
    {
        $pelicula = $horario->pelicula;
        $horarios = collect([$horario]);

        return view('reservas.create', compact('pelicula', 'horarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'asientos' => 'required|string',
        ]);

        $horario = Horario::findOrFail($request->horario_id);
        $asientosSeleccionados = explode(',', $request->asientos);
        $cantidadAsientos = count($asientosSeleccionados);
        
        try {
            DB::beginTransaction();
            
            // Verificar que los asientos no estén ocupados
            $asientosOcupados = AsientoOcupado::getAsientosOcupados($horario->id);
            $asientosConflicto = array_intersect($asientosSeleccionados, $asientosOcupados);
            
            if (!empty($asientosConflicto)) {
                throw new \Exception('Algunos asientos ya han sido reservados: ' . implode(', ', $asientosConflicto));
            }

            // Crear la reserva
            $reserva = Reserva::create([
                'user_id' => auth()->id(),
                'horario_id' => $request->horario_id,
                'asientos' => $request->asientos,
                'cantidad_asientos' => $cantidadAsientos,
                'estado' => 'confirmado',
                'codigo_entrada' => $this->generarCodigoEntrada(),
                'precio_total' => $cantidadAsientos * $horario->precio
            ]);

            // Marcar los asientos como ocupados
            foreach ($asientosSeleccionados as $asiento) {
                AsientoOcupado::create([
                    'horario_id' => $horario->id,
                    'asiento' => $asiento,
                    'tipo' => 'reservado'
                ]);
            }

            DB::commit();

            return redirect()->route('reservas.show', $reserva)
                ->with('success', 'Reserva creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show(Reserva $reserva)
    {
        $this->authorize('view', $reserva);
        return view('reservas.show', compact('reserva'));
    }

    public function destroy(Reserva $reserva)
    {
        try {
            DB::beginTransaction();
            
            // Obtener los asientos de la reserva
            $asientos = explode(',', $reserva->asientos);
            
            // Liberar los asientos en la tabla asientos_ocupados
            AsientoOcupado::where('horario_id', $reserva->horario_id)
                ->whereIn('asiento', $asientos)
                ->where('tipo', 'reservado')
                ->delete();
            
            // Eliminar la reserva
            $reserva->delete();
            
            DB::commit();
            
            return redirect()->route('reservas.index')
                ->with('success', 'Reserva cancelada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al cancelar reserva: ' . $e->getMessage());
            \Log::error('Reserva ID: ' . $reserva->id);
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error al cancelar la reserva. Por favor, contacta al administrador.');
        }
    }

    /**
     * Genera un código único de entrada con 4 números y 4 letras
     */
    private function generarCodigoEntrada()
    {
        do {
            // Generar 4 números aleatorios
            $numeros = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // Generar 4 letras aleatorias (excluyendo caracteres ambiguos)
            $letras = '';
            $caracteresPermitidos = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // Excluimos I, O para evitar confusión
            for ($i = 0; $i < 4; $i++) {
                $letras .= $caracteresPermitidos[rand(0, strlen($caracteresPermitidos) - 1)];
            }
            
            // Combinar números y letras
            $codigo = $numeros . $letras;
            
            // Verificar si el código ya existe
            $existe = Reserva::where('codigo_entrada', $codigo)->exists();
        } while ($existe);

        return $codigo;
    }

    // Formulario para editar reserva
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $usuarios = User::all();
        $horarios = Horario::with('pelicula')->get();
        return view('reservas.edit', compact('reserva', 'usuarios', 'horarios'));
    }

    // Actualizar reserva
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'horario_id' => 'required|exists:horarios,id',
            'asiento' => 'required|string',
        ]);

        $reserva = Reserva::findOrFail($id);
        $reserva->user_id = $request->user_id;
        $reserva->horario_id = $request->horario_id;
        $reserva->asiento = $request->asiento;
        $reserva->estado = $request->estado;
        $reserva->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente');
    }

    public function mostrarQR($id)
    {
        $reserva = Reserva::with('horario.pelicula')->findOrFail($id);
        
        $datosQR = [
            'codigo' => $reserva->codigo_entrada,
            'pelicula' => $reserva->horario->pelicula->titulo,
            'sala' => $reserva->horario->sala,
            'asientos' => $reserva->asientos,
            'hora' => $reserva->horario->hora,
            'fecha' => $reserva->horario->fecha
        ];

        $qrcode = QrCode::size(300)
                       ->backgroundColor(255, 255, 255)
                       ->color(0, 0, 0)
                       ->margin(2)
                       ->generate(json_encode($datosQR));

        return response($qrcode)
               ->header('Content-Type', 'image/svg+xml')
               ->header('Content-Disposition', 'inline; filename="entrada-' . $reserva->codigo_entrada . '.svg"');
    }

    /**
     * Obtiene los asientos ocupados para un horario específico
     */
    public function getAsientosOcupados(Horario $horario)
    {
        $asientosOcupados = AsientoOcupado::where('horario_id', $horario->id)
            ->where(function($query) {
                $query->where('tipo', 'fijo')
                    ->orWhere('tipo', 'reservado')
                    ->orWhere(function($q) {
                        $q->where('tipo', 'temporal')
                            ->where('expires_at', '>', now());
                    });
            })
            ->pluck('asiento')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'asientos' => $asientosOcupados
        ]);
    }
}
