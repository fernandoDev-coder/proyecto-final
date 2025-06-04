<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Usuario Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@takeyourseat.com',
            'password' => Hash::make('Admin2024*'),
            'is_admin' => true,
        ]);

        // Usuario Cliente
        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@takeyourseat.com',
            'password' => Hash::make('Cliente2024*'),
            'is_admin' => false,
        ]);
    }
} 