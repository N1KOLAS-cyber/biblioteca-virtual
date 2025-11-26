<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existen planes
        if (Plan::count() > 0) {
            $this->command->info('Los planes ya existen. Omitiendo seeder.');
            return;
        }

        $plans = [
            [
                'name' => 'Gratuita',
                'slug' => 'gratuita',
                'description' => 'Acceso limitado a libros gratuitos',
                'price' => 0.00,
                'duration_days' => null, // Permanente
                'trial_days' => 0,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Básica',
                'slug' => 'basica',
                'description' => 'Acceso a catálogo limitado de libros',
                'price' => 9.99,
                'duration_days' => 30,
                'trial_days' => 7,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Acceso completo al catálogo de libros',
                'price' => 19.99,
                'duration_days' => 30,
                'trial_days' => 14,
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }

        $this->command->info('Planes creados exitosamente.');
    }
}
