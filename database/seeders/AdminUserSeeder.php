<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'contato@radiocomerciolajense.com.br'],
            [
                'name' => 'Administrador',
                'password' => 'Lajense.!@', // será automaticamente hasheado
            ]
        );
    }
}
