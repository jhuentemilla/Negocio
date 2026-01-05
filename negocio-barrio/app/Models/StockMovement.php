<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'category',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'type' => 'string',
        'category' => 'string',
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
