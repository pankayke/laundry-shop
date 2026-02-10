<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'cloth_type',
        'weight',
        'service_type',
        'price_per_kg',
        'subtotal',
    ];

    protected $casts = [
        'weight'       => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public const SERVICE_TYPES = [
        'wash' => 'Wash',
        'dry'  => 'Dry',
        'fold' => 'Fold',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        return self::SERVICE_TYPES[$this->service_type] ?? $this->service_type;
    }
}
