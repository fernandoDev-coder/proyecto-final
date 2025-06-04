<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    
    protected $table = 'reservas';
    
    protected $fillable = [
        'user_id',
        'horario_id',
        'asientos',
        'cantidad_asientos',
        'estado',
        'codigo_entrada',
        'precio_total'
    ];

    protected $casts = [
        'precio_total' => 'decimal:2'
    ];

    /**
     * Número de reservas por página por defecto
     */
    public const RESERVAS_POR_PAGINA = 6;

    /**
     * Obtiene las reservas paginadas de un usuario
     */
    public static function getReservasPaginadas($userId, $porPagina = null)
    {
        return static::where('user_id', $userId)
            ->with('horario.pelicula')
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina ?? static::RESERVAS_POR_PAGINA);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function pelicula()
    {
        return $this->hasOneThrough(
            Pelicula::class,
            Horario::class,
            'id', // Clave foránea en horarios
            'id', // Clave primaria en peliculas
            'horario_id', // Clave foránea en reservas
            'pelicula_id' // Clave foránea en horarios
        );
    }

    public function getAsientosArrayAttribute()
    {
        return explode(',', $this->asientos);
    }
}
