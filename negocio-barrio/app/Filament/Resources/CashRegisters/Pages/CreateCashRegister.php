<?php

namespace App\Filament\Resources\CashRegisters\Pages;

use App\Filament\Resources\CashRegisters\CashRegisterResource;
use App\Models\CashRegister;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;

class CreateCashRegister extends CreateRecord
{
    protected static string $resource = CashRegisterResource::class;

    public function getTitle(): string
    {
        return 'Crear Caja';
    }

    protected function beforeCreate(): void
    {
        // Validar que el usuario no tenga otra caja abierta
        $existingRegister = CashRegister::where('user_id', Filament::auth()->id())
            ->where('status', 'open')
            ->exists();

        if ($existingRegister) {
            Notification::make()
                ->title('Ya tienes una caja abierta')
                ->body('Cierra la caja actual antes de abrir una nueva.')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
