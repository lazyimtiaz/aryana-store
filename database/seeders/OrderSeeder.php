<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('status', 'published')->get();

        $statuses = [
            ['pending', 'pending'],
            ['confirmed', 'paid'],
            ['processing', 'paid'],
            ['shipped', 'paid'],
            ['delivered', 'paid'],
            ['cancelled', 'pending'],
        ];

        for ($i = 1; $i <= 12; $i++) {
            $customer = $customers[($i - 1) % $customers->count()];

            $subtotal = 0;

            $selectedProducts = $products
                ->shuffle()
                ->take(rand(1, 3));

            $items = [];

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 2);

                $unitPrice = $product->sale_price ?? $product->regular_price;

                $itemSubtotal = $unitPrice * $quantity;

                $subtotal += $itemSubtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discount = $i % 3 === 0 ? min(200, $subtotal * 0.10) : 0;

            $shippingCost = $subtotal >= 3000 ? 0 : 120;

            $total = $subtotal - $discount + $shippingCost;

            [$orderStatus, $paymentStatus] = $statuses[($i - 1) % count($statuses)];

            $order = DB::transaction(function () use (
                $customer,
                $i,
                $subtotal,
                $discount,
                $shippingCost,
                $total,
                $orderStatus,
                $paymentStatus,
                $items
            ) {
                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_number' => 'ORD-' . now()->format('Ym') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),

                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => $customer->phone,

                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'shipping_cost' => $shippingCost,
                    'total' => $total,

                    'payment_method' => 'cod',
                    'payment_status' => $paymentStatus,
                    'order_status' => $orderStatus,

                    'shipping_address' => 'House ' . rand(10, 90) . ', Road ' . rand(1, 15),
                    'shipping_area' => 'GEC',
                    'shipping_city' => 'Chattogram',
                    'shipping_district' => 'Chattogram',
                    'shipping_postal_code' => '4000',

                    'customer_notes' => null,

                    'placed_at' => now()->subDays(rand(1, 30)),
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_variant_id' => null,
                        'product_name' => $item['product']->name,
                        'sku' => $item['product']->sku,
                        'unit_price' => $item['unit_price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                return $order;
            });
        }
    }
}
