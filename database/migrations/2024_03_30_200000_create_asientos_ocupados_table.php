<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asientos_ocupados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->string('asiento');
            $table->enum('tipo', ['fijo', 'reservado', 'temporal'])->default('fijo');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Índice compuesto para búsquedas eficientes
            $table->unique(['horario_id', 'asiento']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asientos_ocupados');
    }
}; 