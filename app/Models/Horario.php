<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $fillable = ['pelicula_id', 'fecha', 'hora', 'sala', 'capacidad_sala'];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class);
    }

    public function asientosOcupados()
    {
        return $this->hasMany(AsientoOcupado::class);
    }

    public function getAsientosOcupadosAttribute()
    {
        return $this->asientosOcupados()
            ->where(function($query) {
                $query->where('tipo', 'fijo')
                    ->orWhere('tipo', 'reservado')
                    ->orWhere(function($q) {
                        $q->where('tipo', 'temporal')
                            ->where('expires_at', '>', now());
                    });
            })
            ->pluck('asiento')
            ->toArray();
    }

    public function getAsientosDisponiblesAttribute()
    {
        $asientosOcupados = $this->asientos_ocupados;
        $totalAsientos = 80; // 8 filas (A-H) x 10 columnas
        return $totalAsientos - count($asientosOcupados);
    }

    public function isAsientoDisponible($asiento)
    {
        return !in_array($asiento, $this->asientos_ocupados);
    }

    public function ocuparAsientosTemporalmente($asientos)
    {
        $asientosOcupados = $this->asientos_ocupados;
        $asientosConflicto = array_intersect($asientos, $asientosOcupados);
        
        if (!empty($asientosConflicto)) {
            throw new \Exception('Algunos asientos ya están ocupados: ' . implode(', ', $asientosConflicto));
        }

        foreach ($asientos as $asiento) {
            AsientoOcupado::create([
                'horario_id' => $this->id,
                'asiento' => $asiento,
                'tipo' => 'temporal',
                'expires_at' => now()->addMinutes(15)
            ]);
        }
    }

    public function liberarAsientosTemporales()
    {
        return $this->asientosOcupados()
            ->where('tipo', 'temporal')
            ->where('expires_at', '<=', now())
            ->delete();
    }
}

