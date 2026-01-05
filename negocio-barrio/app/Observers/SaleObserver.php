<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\CashRegister;

class SaleObserver
{
    /**
     * Handle the Sale "creating" event.
     */
    public function creating(Sale $sale): void
    {
        // Si no está asignada caja, buscar la caja abierta del usuario
        if (!$sale->cash_register_id && $sale->user_id) {
            $openRegister = CashRegister::where('user_id', $sale->user_id)
                ->where('status', 'open')
                ->latest()
                ->first();

            if ($openRegister) {
                $sale->cash_register_id = $openRegister->id;
            }
        }
    }

    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        if ($sale->status === 'completed') {
            $this->decreaseStock($sale);
        }
    }

    /**
     * Handle the Sale "updated" event.
     */
    public function updated(Sale $sale): void
    {
        if ($sale->isDirty('status')) {
            // Si se marca como completada, descontar stock
            if ($sale->status === 'completed') {
                $this->decreaseStock($sale);
            }
            // Si se marca como cancelada, restaurar stock
            elseif ($sale->getOriginal('status') === 'completed' && $sale->status === 'cancelled') {
                $this->restoreStock($sale);
            }
        }
    }

    /**
     * Handle the Sale "deleted" event.
     */
    public function deleted(Sale $sale): void
    {
        if ($sale->status === 'completed') {
            $this->restoreStock($sale);
        }
    }

    /**
     * Handle the Sale "restored" event.
     */
    public function restored(Sale $sale): void
    {
        if ($sale->status === 'completed') {
            $this->decreaseStock($sale);
        }
    }

    /**
     * Handle the Sale "force deleted" event.
     */
    public function forceDeleted(Sale $sale): void
    {
        //
    }

    private function decreaseStock(Sale $sale): void
    {
        foreach ($sale->items as $item) {
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }
    }

    private function restoreStock(Sale $sale): void
    {
        foreach ($sale->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }
    }
}
