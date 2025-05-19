<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Pelicula;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;
use App\Services\ReservaService;

class ReservaController extends Controller
{
    protected $reservaService;

    public function __construct(ReservaService $reservaService)
    {
        $this->middleware('auth');
        $this->reservaService = $reservaService;
    }

    // Mostrar lista de reservas
    public function index(Request $request)
    {
        // Obtener la última reserva si existe en la sesión
        $ultimaReservaId = session('ultima_reserva_id');
        
        if ($ultimaReservaId) {
            // Obtener las reservas ordenando primero la última creada
            $reservas = Reserva::where('user_id', auth()->id())
                ->with('horario.pelicula')
                ->orderByRaw("CASE WHEN id = ? THEN 0 ELSE 1 END", [$ultimaReservaId])
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('por_pagina', Reserva::RESERVAS_POR_PAGINA));
                
            // Limpiar la sesión después de obtener las reservas
            session()->forget('ultima_reserva_id');
        } else {
            // Obtener las reservas normalmente
            $reservas = Reserva::where('user_id', auth()->id())
                ->with('horario.pelicula')
                ->orderBy('created_at', 'desc')
                ->paginate($request->query('por_pagina', Reserva::RESERVAS_POR_PAGINA));
        }

        return view('reservas.index', compact('reservas'));
    }

    // Formulario para crear una nueva reserva (clásico con selectores)
    public function create()
    {
        $usuarios = User::all();
        $horarios = Horario::with('pelicula')->get();
        return view('reservas.create', compact('usuarios', 'horarios'));
    }

    // Guardar nueva reserva (desde formulario clásico)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'horario_id' => 'required|exists:horarios,id',
            'asiento' => 'required|string',
        ]);

        Reserva::create([
            'user_id' => $request->user_id,
            'horario_id' => $request->horario_id,
            'asiento' => $request->asiento,
            'estado' => 'reservado',
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente');
    }

    // NUEVO: Formulario para reservar desde el horario seleccionado
    public function createDesdeHorario($horarioId)
    {
        $horario = Horario::findOrFail($horarioId);
        $pelicula = $horario->pelicula;

        return view('reservas.create', compact('horario', 'pelicula'));
    }

    // Guardar reserva desde el flujo visual con asiento seleccionado
    public function storeDesdeHorario(Request $request, Horario $horario)
    {
        $request->validate([
            'asientos' => 'required|array|min:1',
            'asientos.*' => 'required|string|max:10',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para realizar una reserva.');
        }

        try {
            // Usar el servicio para crear la reserva
            $reserva = $this->reservaService->crearReserva([
                'horario_id' => $horario->id,
                'asientos' => $request->asientos
            ], $user);

            // Almacenar el ID de la reserva en la sesión
            session(['ultima_reserva_id' => $reserva->id]);
            session()->save();

            return redirect()->to('/reservas')
                ->with('success', '¡Reserva confirmada! Has reservado ' . 
                    count($request->asientos) . ' asiento(s) por ' . 
                    number_format($reserva->precio_total, 2, ',', '.') . '€');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al procesar la reserva: ' . $e->getMessage())
                ->withInput();
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

    // Mostrar detalles de reserva
    public function show($id)
    {
        $reserva = Reserva::with('user', 'horario.pelicula')->findOrFail($id);
        return view('reservas.show', compact('reserva'));
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

    // Eliminar reserva
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->delete();

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada correctamente');
    }

    public function mostrarQR($id)
    {
        $reserva = Reserva::with('horario.pelicula')->findOrFail($id);
        
        // Verificar que el usuario actual es el dueño de la reserva
        if ($reserva->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este QR');
        }

        $datosQR = [
            'codigo' => $reserva->codigo_entrada,
            'pelicula' => $reserva->horario->pelicula->titulo,
            'sala' => $reserva->horario->sala,
            'asientos' => $reserva->asientos,
            'hora' => $reserva->horario->hora,
            'fecha' => $reserva->horario->fecha
        ];

        $qrcode = QrCode::size(200)
                       ->backgroundColor(255, 255, 255)
                       ->color(0, 0, 0)
                       ->margin(1)
                       ->generate(json_encode($datosQR));

        return response($qrcode)->header('Content-Type', 'image/svg+xml');
    }
}
