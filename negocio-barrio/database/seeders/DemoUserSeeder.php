<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
            ]);
            
            if ($adminRole) {
                $admin->roles()->attach($adminRole->id);
            }
        }

        $vendedorRole = Role::where('name', 'Vendedor')->first();
        
        if (!User::where('email', 'vendedor@example.com')->exists()) {
            $vendedor = User::create([
                'name' => 'Vendedor Demo',
                'email' => 'vendedor@example.com',
                'password' => bcrypt('password123'),
            ]);
            
            if ($vendedorRole) {
                $vendedor->roles()->attach($vendedorRole->id);
            }
        }
    }
}
