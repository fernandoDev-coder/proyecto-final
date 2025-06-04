<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsientoOcupado extends Model
{
    use HasFactory;

    protected $table = 'asientos_ocupados';

    protected $fillable = [
        'horario_id',
        'asiento',
        'tipo',
        'expires_at'
    ];

    protected $dates = [
        'expires_at',
        'created_at',
        'updated_at'
    ];

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    /**
     * Genera asientos ocupados fijos aleatorios para un horario
     */
    public static function generarAsientosFijos($horarioId, $cantidad = 10)
    {
        $filas = range('A', 'H');
        $columnas = range(1, 10);
        $asientosDisponibles = [];

        // Generar todas las combinaciones posibles de asientos
        foreach ($filas as $fila) {
            foreach ($columnas as $columna) {
                $asientosDisponibles[] = $fila . $columna;
            }
        }

        // Mezclar aleatoriamente y tomar la cantidad necesaria
        shuffle($asientosDisponibles);
        $asientosSeleccionados = array_slice($asientosDisponibles, 0, $cantidad);

        // Crear los registros de asientos ocupados
        foreach ($asientosSeleccionados as $asiento) {
            self::create([
                'horario_id' => $horarioId,
                'asiento' => $asiento,
                'tipo' => 'fijo'
            ]);
        }
    }

    /**
     * Obtiene todos los asientos ocupados (fijos y reservados) para un horario
     */
    public static function getAsientosOcupados($horarioId)
    {
        return self::where('horario_id', $horarioId)
            ->where(function($query) {
                $query->where('tipo', 'fijo')
                    ->orWhere('tipo', 'reservado')
                    ->orWhere(function($q) {
                        $q->where('tipo', 'temporal')
                            ->where('expires_at', '>', Carbon::now());
                    });
            })
            ->orderBy('created_at', 'desc')
            ->pluck('asiento')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Marca asientos como temporalmente ocupados
     */
    public static function ocuparTemporalmente($horarioId, $asientos)
    {
        $expiracion = Carbon::now()->addMinutes(15);
        
        foreach ($asientos as $asiento) {
            self::create([
                'horario_id' => $horarioId,
                'asiento' => $asiento,
                'tipo' => 'temporal',
                'expires_at' => $expiracion
            ]);
        }
    }

    /**
     * Confirma asientos temporales como reservados
     */
    public static function confirmarAsientos($horarioId, $asientos)
    {
        return self::where('horario_id', $horarioId)
            ->whereIn('asiento', $asientos)
            ->where('tipo', 'temporal')
            ->update([
                'tipo' => 'reservado',
                'expires_at' => null
            ]);
    }

    /**
     * Libera los asientos temporales expirados
     */
    public static function liberarAsientosExpirados()
    {
        return self::where('tipo', 'temporal')
            ->where('expires_at', '<=', Carbon::now())
            ->delete();
    }
} 