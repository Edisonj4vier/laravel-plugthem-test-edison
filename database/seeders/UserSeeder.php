<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@plugthem.test',
            'password' => Hash::make('password123'),
        ]);

        // Usuarios de prueba
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@test.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'María González',
            'email' => 'maria@test.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos@test.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Ana Martínez',
            'email' => 'ana@test.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
