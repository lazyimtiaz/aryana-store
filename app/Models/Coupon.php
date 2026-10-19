<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function validationErrorFor(
        int $userId,
        float $subtotal
    ): ?string {
        if (! $this->is_active) {
            return 'This coupon is no longer active.';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'This coupon is not available yet.';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'This coupon has expired.';
        }

        if (
            $this->minimum_order_amount !== null &&
            $subtotal < (float) $this->minimum_order_amount
        ) {
            return 'Your order does not meet the minimum spend for this coupon.';
        }

        if (
            $this->usage_limit !== null &&
            $this->usages()->count() >= $this->usage_limit
        ) {
            return 'This coupon has reached its usage limit.';
        }

        if ($this->usage_limit_per_user !== null) {
            $userUsageCount = $this->usages()
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $this->usage_limit_per_user) {
                return 'You have already used this coupon the maximum number of times.';
            }
        }

        if (! in_array($this->type, ['fixed', 'percentage', 'percent'], true)) {
            return 'This coupon has an unsupported discount type.';
        }

        if ((float) $this->value <= 0) {
            return 'This coupon does not have a valid discount value.';
        }

        return null;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (in_array($this->type, ['percentage', 'percent'], true)) {
            $discount = $subtotal * ((float) $this->value / 100);
        } else {
            $discount = (float) $this->value;
        }

        if ($this->maximum_discount_amount !== null) {
            $discount = min(
                $discount,
                (float) $this->maximum_discount_amount
            );
        }

        return round(min($discount, max(0, $subtotal)), 2);
    }
}
