<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Horario;
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
            
            // Verificar si algún asiento ya está reservado
            $reservasExistentes = Reserva::where('horario_id', $horario->id)
                ->where('estado', 'confirmado')
                ->get();

            foreach ($reservasExistentes as $reserva) {
                $asientosOcupados = explode(',', $reserva->asientos);
                $asientosConflicto = array_intersect($asientosSeleccionados, $asientosOcupados);
                
                if (!empty($asientosConflicto)) {
                    throw new \Exception('Algunos asientos ya han sido reservados: ' . implode(', ', $asientosConflicto));
                }
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

            DB::commit();
            return $reserva;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

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
} 