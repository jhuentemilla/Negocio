<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\CashRegister;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;

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

        // 4. ASIGNAR CAJA
        $data['cash_register_id'] = $activeRegister->id;

        return $data;
    }
}
