<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar las rutas de las imágenes
        DB::table('peliculas')
            ->whereRaw("imagen LIKE '/storage/app/private/public/peliculas/%'")
            ->update([
                'imagen' => DB::raw("REPLACE(imagen, '/storage/app/private/public/peliculas/', '/storage/peliculas/')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir las rutas de las imágenes
        DB::table('peliculas')
            ->whereRaw("imagen LIKE '/storage/peliculas/%'")
            ->update([
                'imagen' => DB::raw("REPLACE(imagen, '/storage/peliculas/', '/storage/app/private/public/peliculas/')")
            ]);
    }
};
