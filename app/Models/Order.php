<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'staff_id',
        'status',
        'total_weight',
        'total_price',
        'payment_status',
        'payment_method',
        'amount_paid',
        'change_amount',
        'notes',
        'estimated_weight',
        'requested_services',
        'special_instructions',
        'payment_reference',
    ];

    protected $casts = [
        'total_weight'      => 'decimal:2',
        'total_price'       => 'decimal:2',
        'amount_paid'       => 'decimal:2',
        'change_amount'     => 'decimal:2',
        'estimated_weight'  => 'decimal:2',
        'requested_services' => 'array',
    ];

    /** Ordered status progression used for dropdowns and timelines. */
    public const STATUSES = [
        'pending_approval' => 'Pending Approval',
        'received'         => 'Received',
        'washing'          => 'Washing',
        'drying'           => 'Drying',
        'folding'          => 'Folding',
        'ready_for_pickup' => 'Ready for Pickup',
        'collected'        => 'Collected',
        'cancelled'        => 'Cancelled',
    ];

    /** Staff-managed statuses (excludes pending_approval, cancelled which are customer/system-managed). */
    public static function staffStatuses(): array
    {
        return array_diff_key(self::STATUSES, array_flip(['pending_approval', 'cancelled']));
    }

    public const PAYMENT_METHODS = [
        'cash'   => 'Cash',
        'gcash'  => 'GCash',
        'maya'   => 'Maya',
        'unpaid' => 'Unpaid',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /** 1-based step number for the timeline component. */
    public function getStatusStepAttribute(): int
    {
        $keys = array_keys(self::STATUSES);

        return array_search($this->status, $keys, true) + 1;
    }

    // ── Query helpers ────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isReadyForPickup(): bool
    {
        return $this->status === 'ready_for_pickup';
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
