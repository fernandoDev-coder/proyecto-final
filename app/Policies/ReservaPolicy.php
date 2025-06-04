<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    public function view(User $user, Reserva $reserva)
    {
        return true;
    }

    public function delete(User $user, Reserva $reserva)
    {
        return true;
    }
} 