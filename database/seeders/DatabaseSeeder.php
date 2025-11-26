<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Primero crear los roles
        $this->call([
            RoleSeeder::class,
        ]);

        // Crear usuario admin
        $admin = User::factory()->create([
            'name' => 'Nicolas Gamboa',
            'email' => 'prueba0@gmail.com',
            'password' => bcrypt('prueba1230'),
            'role' => 'admin',
        ]);

        // Asignar rol de admin
        $admin->assignRole('admin');
    }
}
