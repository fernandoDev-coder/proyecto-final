<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AsientoOcupado;
use App\Models\Horario;

class LimpiarAsientosOcupados extends Command
{
    protected $signature = 'asientos:limpiar {horario_id? : ID del horario específico a limpiar}';
    protected $description = 'Limpia los asientos ocupados temporales y/o fijos de uno o todos los horarios';

    public function handle()
    {
        $horarioId = $this->argument('horario_id');

        if ($horarioId) {
            $horario = Horario::find($horarioId);
            if (!$horario) {
                $this->error("No se encontró el horario con ID {$horarioId}");
                return 1;
            }

            $count = AsientoOcupado::where('horario_id', $horarioId)->delete();
            $this->info("Se han eliminado {$count} asientos ocupados del horario {$horarioId}");
        } else {
            if ($this->confirm('¿Estás seguro de que quieres eliminar TODOS los asientos ocupados de TODOS los horarios?')) {
                $count = AsientoOcupado::truncate();
                $this->info('Se han eliminado todos los asientos ocupados');
            }
        }

        return 0;
    }
} 