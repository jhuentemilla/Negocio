<?php

/**
 * SETUP DE PERMISOS PARA LAS POLICIES
 * 
 * Ejecuta estos comandos en Tinker para crear los permisos necesarios:
 * 
 * php artisan tinker
 * 
 * Luego copia y ejecuta:
 */

// PRODUCTOS
// $p = App\Models\Permission::create(['name' => 'create_products', 'description' => 'Crear productos', 'resource' => ['products']]);
// $p = App\Models\Permission::create(['name' => 'update_products', 'description' => 'Editar productos', 'resource' => ['products']]);
// $p = App\Models\Permission::create(['name' => 'delete_products', 'description' => 'Borrar productos', 'resource' => ['products']]);

// VENTAS
// $p = App\Models\Permission::create(['name' => 'create_sales', 'description' => 'Crear ventas', 'resource' => ['sales']]);
// $p = App\Models\Permission::create(['name' => 'update_sales', 'description' => 'Editar ventas', 'resource' => ['sales']]);
// $p = App\Models\Permission::create(['name' => 'delete_sales', 'description' => 'Borrar ventas', 'resource' => ['sales']]);

// CAJA Y TURNOS
// $p = App\Models\Permission::create(['name' => 'create_cash_registers', 'description' => 'Abrir caja', 'resource' => ['cash_registers']]);
// $p = App\Models\Permission::create(['name' => 'update_cash_registers', 'description' => 'Actualizar caja', 'resource' => ['cash_registers']]);
// $p = App\Models\Permission::create(['name' => 'delete_cash_registers', 'description' => 'Eliminar caja', 'resource' => ['cash_registers']]);

// USUARIOS (solo para Admin)
// $p = App\Models\Permission::create(['name' => 'view_users', 'description' => 'Ver usuarios', 'resource' => ['users']]);
// $p = App\Models\Permission::create(['name' => 'create_users', 'description' => 'Crear usuarios', 'resource' => ['users']]);
// $p = App\Models\Permission::create(['name' => 'update_users', 'description' => 'Editar usuarios', 'resource' => ['users']]);
// $p = App\Models\Permission::create(['name' => 'delete_users', 'description' => 'Borrar usuarios', 'resource' => ['users']]);

// HISTORIAL DE STOCK
// $p = App\Models\Permission::create(['name' => 'delete_stock_movements', 'description' => 'Borrar movimientos de stock', 'resource' => ['stock_movements']]);

/**
 * EJEMPLO: Asignar permisos a un rol
 * 
 * // Obtener el rol "Vendedor"
 * $vendedorRole = App\Models\Role::where('name', 'Vendedor')->first();
 * 
 * // Asignar solo create_sales
 * $createSalesPermission = App\Models\Permission::where('name', 'create_sales')->first();
 * $vendedorRole->permissions()->attach($createSalesPermission->id);
 * 
 * // Ahora ese vendedor SOLO puede crear ventas, no editar ni borrar
 */

/**
 * VERIFICAR PERMISOS ASIGNADOS
 * 
 * $role = App\Models\Role::where('name', 'Vendedor')->first();
 * $role->permissions()->pluck('name'); // Ver nombres de permisos asignados
 */
