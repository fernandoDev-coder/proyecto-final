<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('horario_id')->constrained('horarios')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('asientos');
            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado'])->default('confirmado');
            $table->string('codigo_entrada', 8)->unique();
            $table->decimal('precio_total', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
};

