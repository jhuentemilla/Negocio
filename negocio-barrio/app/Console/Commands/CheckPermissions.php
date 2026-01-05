<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar permisos del rol Vendedor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== ROL VENDEDOR ===');
        $vendedor = Role::where('name', 'Vendedor')->first();
        
        if ($vendedor) {
            $this->info('Permisos asignados:');
            $permissions = $vendedor->permissions;
            
            if ($permissions->isEmpty()) {
                $this->warn('SIN PERMISOS ASIGNADOS');
            } else {
                foreach ($permissions as $perm) {
                    $resources = json_encode($perm->resource);
                    $this->line("- {$perm->name}: {$resources}");
                }
            }
        } else {
            $this->error('Rol Vendedor no encontrado');
        }
        
        $this->newLine();
        $this->info('=== USUARIOS CON ROL VENDEDOR ===');
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Vendedor');
        })->get();
        
        if ($users->isEmpty()) {
            $this->warn('No hay usuarios con rol Vendedor');
        } else {
            foreach ($users as $user) {
                $this->line("- {$user->name} ({$user->email})");
            }
        }
        
        $this->newLine();
        $this->info('=== TODOS LOS PERMISOS ===');
        $allPerms = Permission::all();
        foreach ($allPerms as $perm) {
            $resources = json_encode($perm->resource);
            $this->line("- {$perm->name}: {$resources}");
        }
    }
}
