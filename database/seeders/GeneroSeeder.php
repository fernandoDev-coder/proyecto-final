<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genero;

class GeneroSeeder extends Seeder
{
    public function run()
    {
        $generos = [
            'Acción',
            'Aventura',
            'Comedia',
            'Drama',
            'Ciencia ficción',
            'Terror',
            'Romance',
            'Documental',
            'Animación',
            'Suspenso',
            'Crimen'
        ];

        foreach ($generos as $genero) {
            Genero::create(['nombre' => $genero]);
        }
    }
} 