<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelicula_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora');
            $table->string('sala');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}

