<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 1000,
            'maximum_discount_amount' => 500,
            'usage_limit' => 1000,
            'usage_limit_per_user' => 1,
            'starts_at' => now()->subDays(30),
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE200',
            'type' => 'fixed',
            'value' => 200,
            'minimum_order_amount' => 2000,
            'maximum_discount_amount' => null,
            'usage_limit' => 500,
            'usage_limit_per_user' => 2,
            'starts_at' => now()->subDays(10),
            'expires_at' => now()->addMonths(2),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'GLOW15',
            'type' => 'percentage',
            'value' => 15,
            'minimum_order_amount' => 3000,
            'maximum_discount_amount' => 700,
            'usage_limit' => 250,
            'usage_limit_per_user' => 1,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}
