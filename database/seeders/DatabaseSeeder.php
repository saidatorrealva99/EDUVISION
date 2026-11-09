<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles y permisos
        $this->call(RolesYPermisosSeeder::class);

        // 2. Usuario administrador (acceso total al sistema)
        User::factory()->create([
            'dni' => '40000001',
            'nombres' => 'Administrador',
            'apellidos' => 'del Sistema',
            'email' => 'admin@eduvision.test',
        ])->assignRole('administrador');

        // Aquí iremos agregando los demás seeders (catálogos, personal, estudiantes...)
    }
}