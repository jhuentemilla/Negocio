<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
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
