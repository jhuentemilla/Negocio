<?php

namespace App\Filament\Resources\CashRegisters\Pages;

use App\Filament\Resources\CashRegisters\CashRegisterResource;
use App\Models\CashRegister;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCashRegister extends EditRecord
{
    protected static string $resource = CashRegisterResource::class;

    public function getTitle(): string
    {
        return 'Editar Caja';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('close_register')
                ->label('Cerrar Caja')
                ->color('danger')
                ->icon('heroicon-o-lock-closed')
                ->requiresConfirmation()
                ->form([
                    TextInput::make('closing_balance')
                        ->label('Dinero contado en caja')
                        ->numeric()
                        ->required()
                        ->input('number'),
                ])
                ->action(function (array $data) {
                    $record = $this->record;
                    // Usamos los métodos maravillosos del modelo
                    $record->closing_balance = $data['closing_balance'];
                    $record->expected_total = $record->calculateExpectedTotal();
                    $record->difference = $record->calculateDifference();
                    $record->status = 'closed';
                    $record->closed_at = now();
                    $record->save();

                    Notification::make()
                        ->title('Caja cerrada exitosamente')
                        ->success()
                        ->send();

                    $this->redirect(CashRegisterResource::getUrl('index'));
                })
                // Solo mostrar si está abierta
                ->visible(fn () => $this->record->status === 'open'),
            DeleteAction::make(),
        ];
    }
}
