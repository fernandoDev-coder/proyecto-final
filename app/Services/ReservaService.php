<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\AsientoOcupado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservaService
{
    public function crearReserva($datos, $user)
    {
        try {
            DB::beginTransaction();

            // Validar que los asientos no estén ya reservados
            $horario = Horario::findOrFail($datos['horario_id']);
            $asientosSeleccionados = $datos['asientos'];
            
            // Verificar si algún asiento ya está ocupado (fijo o reservado)
            $asientosOcupados = $horario->getAsientosOcupados();
            $asientosConflicto = array_intersect($asientosSeleccionados, $asientosOcupados);
            
            if (!empty($asientosConflicto)) {
                throw new \Exception('Algunos asientos ya han sido reservados: ' . implode(', ', $asientosConflicto));
            }

            // Calcular precio total
            $precio_por_asiento = 8.00;
            $precio_total = count($asientosSeleccionados) * $precio_por_asiento;

            // Crear la reserva
            $reserva = Reserva::create([
                'user_id' => $user->id,
                'horario_id' => $horario->id,
                'asientos' => implode(',', $asientosSeleccionados),
                'estado' => 'confirmado',
                'precio_total' => $precio_total,
                'codigo_entrada' => $this->generarCodigoEntrada()
            ]);

            // Confirmar los asientos como reservados
            foreach ($asientosSeleccionados as $asiento) {
                AsientoOcupado::updateOrCreate(
                    [
                        'horario_id' => $horario->id,
                        'asiento' => $asiento
                    ],
                    [
                        'tipo' => 'reservado',
                        'expires_at' => null
                    ]
                );
            }

            DB::commit();
            return $reserva;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Ocupa temporalmente los asientos seleccionados
     */
    public function ocuparAsientosTemporalmente($horarioId, $asientos)
    {
        try {
            DB::beginTransaction();

            // Verificar si los asientos están disponibles
            $horario = Horario::findOrFail($horarioId);
            $asientosOcupados = $horario->getAsientosOcupados();
            $asientosConflicto = array_intersect($asientos, $asientosOcupados);
            
            if (!empty($asientosConflicto)) {
                throw new \Exception('Algunos asientos ya están ocupados: ' . implode(', ', $asientosConflicto));
            }

            // Ocupar los asientos temporalmente
            AsientoOcupado::ocuparTemporalmente($horarioId, $asientos);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generarCodigoEntrada()
    {
        do {
            $codigo = strtoupper(Str::random(8));
            $existe = Reserva::where('codigo_entrada', $codigo)->exists();
        } while ($existe);

        return $codigo;
    }
} 