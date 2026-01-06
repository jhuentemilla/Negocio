<?php

namespace App\Observers;

use App\Models\SaleItem;
use App\Models\StockMovement;

class SaleItemObserver
{
    /**
     * Handle the SaleItem "created" event.
     */
    public function created(SaleItem $saleItem): void
    {
        // Crear movimiento de salida (cantidad negativa para restar del stock)
        StockMovement::create([
            'product_id' => $saleItem->product_id,
            'type' => 'salida',
            'category' => 'venta',
            'quantity' => -($saleItem->quantity),
            'notes' => 'Venta #' . $saleItem->sale_id,
        ]);
    }

    /**
     * Handle the SaleItem "updated" event.
     */
    public function updated(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "deleted" event.
     */
    public function deleted(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "restored" event.
     */
    public function restored(SaleItem $saleItem): void
    {
        //
    }

    /**
     * Handle the SaleItem "force deleted" event.
     */
    public function forceDeleted(SaleItem $saleItem): void
    {
        //
    }
}
