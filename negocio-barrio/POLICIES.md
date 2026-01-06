# Policies de Autorización (Authorization)

## ¿Qué son las Policies?

Las Policies son clases en Laravel que centralizan la lógica de autorización. En lugar de esparcir permisos en todo el código, definen qué puede hacer cada usuario con cada modelo.

## Policies Implementadas

### 1. **ProductPolicy** (`app/Policies/ProductPolicy.php`)
- `viewAny()` - Ver listado de productos
- `view()` - Ver un producto específico
- `create()` - Crear un producto nuevo → requiere permiso `create_products`
- `update()` - Editar un producto → requiere permiso `update_products`
- `delete()` - Borrar un producto → requiere permiso `delete_products`

### 2. **SalePolicy** (`app/Policies/SalePolicy.php`)
- `viewAny()` - Ver listado de ventas
- `view()` - Ver una venta específica
- `create()` - Crear una venta → requiere permiso `create_sales`
- `update()` - Editar una venta → requiere permiso `update_sales`
- `delete()` - Borrar una venta → requiere permiso `delete_sales`

### 3. **CashRegisterPolicy** (`app/Policies/CashRegisterPolicy.php`)
- `viewAny()` - Ver cajas
- `view()` - Ver una caja específica
- `create()` - Abrir una caja → requiere permiso `create_cash_registers`
- `update()` - Actualizar estado de caja → requiere permiso `update_cash_registers`
- `delete()` - Eliminar caja → requiere permiso `delete_cash_registers`

### 4. **UserPolicy** (`app/Policies/UserPolicy.php`)
- `viewAny()` - Ver usuarios → requiere permiso `view_users`
- `view()` - Ver un usuario → requiere permiso `view_users`
- `create()` - Crear usuario → requiere permiso `create_users`
- `update()` - Editar usuario → requiere permiso `update_users`
- `delete()` - Borrar usuario → requiere permiso `delete_users`

### 5. **StockMovementPolicy** (`app/Policies/StockMovementPolicy.php`)
- `viewAny()` - Ver historial de stock
- `view()` - Ver un movimiento específico
- `create()` - **BLOQUEADO** (los movimientos se crean automáticamente)
- `update()` - **BLOQUEADO** (los movimientos no se editan)
- `delete()` - Borrar un movimiento → requiere permiso `delete_stock_movements`

## Cómo Funcionan

1. **Conexión con Base de Datos**: Las Policies usan `PermissionService` que verifica los permisos almacenados en la BD
2. **Flujo**: Usuario → Roles → Permisos → Acciones permitidas
3. **Admin**: Los usuarios con rol "Admin" tienen acceso a todo automáticamente

## Ejemplo: Vendedor No Puede Editar Productos

Si un usuario tiene rol "Vendedor" sin permiso `update_products`:

```php
// En Filament, cuando intente editar:
$user->can('update', $product); // → false → Botón EDIT oculto
```

## Permisos Necesarios en BD

Asegúrate de que en la tabla `permissions` tengas estos registros:

```
create_products
update_products
delete_products

create_sales
update_sales
delete_sales

create_cash_registers
update_cash_registers
delete_cash_registers

view_users
create_users
update_users
delete_users

delete_stock_movements
```

## Registración (Ya Hecha)

Las Policies están registradas en `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    Product::class => ProductPolicy::class,
    Sale::class => SalePolicy::class,
    CashRegister::class => CashRegisterPolicy::class,
    User::class => UserPolicy::class,
    StockMovement::class => StockMovementPolicy::class,
];
```

## Integración con Filament

Filament **respeta automáticamente las Policies** de Laravel. Esto significa:

- Los botones Create/Edit/Delete se ocultan si el usuario no tiene permiso
- Si intenta acceder directamente por URL, será bloqueado
- Los reportes solo muestran datos que puede ver

## Prueba de Seguridad

Para probar que funciona:

1. Crea un usuario con rol "Cajero"
2. Intenta entrar a "Productos" → debe ver lista, pero sin botón de crear/editar
3. Si intenta ir a `/admin/products/create`, debe ser rechazado
4. Intenta editar un usuario como "Vendedor" → debe ser bloqueado

**Eso demuestra que el sistema es seguro** 🔒
