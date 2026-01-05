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
        'payment_method',
        'cash_register_id',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Sale $sale) {
            // Si ya trae caja (ej: asignada manualmente), no hacemos nada
            if ($sale->cash_register_id) {
                return;
            }

            // Buscamos la caja abierta del usuario que está vendiendo
            $activeRegister = CashRegister::where('user_id', $sale->user_id)
                ->where('status', 'open')
                ->latest()
                ->first();

            // Asignamos la venta a esa caja
            if ($activeRegister) {
                $sale->cash_register_id = $activeRegister->id;
            }
        });
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
