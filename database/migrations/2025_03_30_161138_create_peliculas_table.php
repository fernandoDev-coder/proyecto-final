<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeliculasTable extends Migration
{
    public function up()
    {
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('genero');
            $table->string('imagen')->nullable();
            $table->integer('duracion')->default(120); // Duración en minutos
            $table->string('clasificacion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peliculas');
    }
}
