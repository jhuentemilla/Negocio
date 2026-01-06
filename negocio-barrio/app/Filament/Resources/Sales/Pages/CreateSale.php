<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\CashRegister;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. OBTENER USUARIO: Usamos Filament::auth() que es 100% confiable en el panel
        $userId = Filament::auth()->id();

        // Inyectamos el ID real del servidor, ignorando lo que venga del formulario
        $data['user_id'] = $userId;

        // 2. BUSCAR CAJA: Usamos ese ID seguro
        $activeRegister = CashRegister::where('user_id', $userId)
            ->where('status', 'open')
            ->latest()
            ->first();

        // 3. VALIDAR: Si no hay caja, detenemos el proceso
        if (!$activeRegister) {
            Notification::make()
                ->title('Caja Cerrada')
                ->body('No puedes vender sin tener una caja abierta.')
                ->danger()
                ->send();

            $this->halt();
        }

        // 4. VALIDAR STOCK: Verificar que hay suficiente inventario
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if ($product && $item['quantity'] > $product->quantity) {
                    Notification::make()
                        ->title('Stock Insuficiente')
                        ->body("Solo hay {$product->quantity} unidades de '{$product->name}'")
                        ->danger()
                        ->send();

                    $this->halt();
                }
            }
        }

        // 5. ASIGNAR CAJA
        $data['cash_register_id'] = $activeRegister->id;

        return $data;
    }

    /**
     * Manejar la creación del registro con transacción de base de datos
     * Asegura que si algo falla, nada se guarda (todo o nada)
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Si algo falla aquí, la transacción se revierte automáticamente
            return static::getModel()::create($data);
        });    }
}