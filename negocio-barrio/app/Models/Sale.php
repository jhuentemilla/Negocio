<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'notes',
        'status',
        'sold_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function calculateTotal(): void
    {
        $this->total = $this->items->sum('subtotal');
        $this->save();
    }

    public function cancel(): void
    {
        // Devolver stock a los productos
        foreach ($this->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }

        // Marcar venta como cancelada
        $this->status = 'cancelled';
        $this->save();
    }
}
