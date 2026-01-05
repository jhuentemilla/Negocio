<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    protected $fillable = [
        'user_id',
        'opening_balance',
        'closing_balance',
        'expected_total',
        'difference',
        'status',
        'notes',
        'opened_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'closing_balance' => 'decimal:2',
            'expected_total' => 'decimal:2',
            'difference' => 'decimal:2',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Obtiene el total de ventas en efectivo de esta caja
     */
    public function getCashSalesTotalAttribute(): float
    {
        return $this->sales()
            ->where('payment_method', 'cash')
            ->sum('total');
    }

    /**
     * Calcula el total esperado (saldo inicial + ventas en efectivo)
     */
    public function calculateExpectedTotal(): float
    {
        return (float) ($this->opening_balance + $this->getCashSalesTotalAttribute());
    }

    /**
     * Calcula la diferencia entre lo esperado y lo que se cuenta
     */
    public function calculateDifference(): float
    {
        return (float) ($this->closing_balance - $this->calculateExpectedTotal());
    }
}
