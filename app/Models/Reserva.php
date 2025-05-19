<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    
    protected $table = 'reservas';
    
    protected $fillable = [
        'horario_id',
        'user_id',
        'asientos',
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
}
