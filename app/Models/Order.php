<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'discount',
        'shipping_cost',
        'total',
        'payment_method',
        'payment_reference',
        'payment_payer_number',
        'payment_status',
        'payment_verified_at',
        'payment_verified_by',
        'payment_verification_notes',
        'order_status',
        'shipping_address',
        'shipping_area',
        'shipping_city',
        'shipping_district',
        'shipping_postal_code',
        'customer_notes',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
            'payment_verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }
}
