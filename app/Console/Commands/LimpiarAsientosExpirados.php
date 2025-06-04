<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AsientoOcupado;

class LimpiarAsientosExpirados extends Command
{
    protected $signature = 'asientos:limpiar';
    protected $description = 'Libera los asientos temporales que han expirado';

    public function handle()
    {
        $this->info('Iniciando limpieza de asientos temporales expirados...');
        
        $cantidad = AsientoOcupado::liberarAsientosExpirados();
        
        $this->info("Se han liberado {$cantidad} asientos temporales expirados.");
    }
} 