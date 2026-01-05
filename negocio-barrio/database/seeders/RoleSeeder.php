<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrador con acceso total']
        );

        Role::firstOrCreate(
            ['name' => 'Vendedor'],
            ['description' => 'Vendedor que puede crear y gestionar ventas']
        );
    }
}
