<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model {
    use HasFactory;
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'genero',
        'imagen',
        'duracion',
        'clasificacion'
    ];

    public function horarios() {
        return $this->hasMany(Horario::class);
    }
}

