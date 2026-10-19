<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use App\Models\InventoryTransaction;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function create(): View
    {
        $user = auth()->user();

        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->with('items.product.primaryImage')
            ->first();

        abort_unless(
            $cart && $cart->items->isNotEmpty(),
            404
        );

        $addresses = $user->addresses()
            ->latest()
            ->get();

        $subtotal = (float) $cart->items->sum(
            fn ($item) => $item->quantity * $item->unit_price
        );

        $appliedCoupon = null;
        $couponDiscount = 0.0;

        $couponCode = session('applied_coupon.code');

        if ($couponCode) {
            $coupon = Coupon::query()
                ->whereRaw('UPPER(code) = ?', [strtoupper($couponCode)])
                ->first();

            if (! $coupon) {
                session()->forget('applied_coupon');
            } else {
                $couponError = $coupon->validationErrorFor(
                    $user->id,
                    $subtotal
                );

                if ($couponError !== null) {
                    session()->forget('applied_coupon');
                } else {
                    $appliedCoupon = $coupon;
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                }
            }
        }

        return view('checkout.create', [
            'cart' => $cart,
            'addresses' => $addresses,
            'subtotal' => $subtotal,
            'appliedCoupon' => $appliedCoupon,
            'couponDiscount' => $couponDiscount,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $couponCode = $request->session()->get('applied_coupon.code');

        $order = DB::transaction(function () use (
            $request,
            $couponCode
        ): Order {
            $user = $request->user();

            $cart = Cart::query()
                ->where('user_id', $user->id)
                ->with('items.product')
                ->lockForUpdate()
                ->firstOrFail();

            abort_if(
                $cart->items->isEmpty(),
                422,
                'Your bag is empty.'
            );

            $address = $user->addresses()
                ->whereKey($request->integer('address_id'))
                ->firstOrFail();

            foreach ($cart->items as $item) {
                $product = $item->product()
                    ->lockForUpdate()
                    ->firstOrFail();

                abort_if(
                    $product->status !== 'published',
                    422,
                    "{$product->name} is no longer available."
                );

                abort_if(
                    $product->stock_quantity < $item->quantity,
                    422,
                    "{$product->name} is no longer available in that quantity."
                );

                // Keep the locked product instance for the stock update below.
                $item->setRelation('product', $product);
            }

            $subtotal = (float) $cart->items->sum(
                fn ($item) => $item->quantity * $item->unit_price
            );

            $coupon = null;
            $discount = 0.0;

            if ($couponCode) {
                $coupon = Coupon::query()
                    ->where('code', $couponCode)
                    ->lockForUpdate()
                    ->first();

                if (! $coupon) {
                    throw ValidationException::withMessages([
                        'coupon_code' => 'This coupon is no longer available. Please apply another coupon.',
                    ]);
                }

                $couponError = $coupon->validationErrorFor(
                    $user->id,
                    $subtotal
                );

                if ($couponError !== null) {
                    throw ValidationException::withMessages([
                        'coupon_code' => $couponError,
                    ]);
                }

                $discount = $coupon->calculateDiscount($subtotal);
            }

            $shipping = $request->string('delivery_method')->toString() === 'express'
                ? 150
                : 80;

            $total = max(0, $subtotal - $discount) + $shipping;

            $order = Order::query()->create([
                'user_id' => $user->id,
                'order_number' => 'ARY-'
                    . now()->format('ymd')
                    . '-'
                    . str()->upper(str()->random(5)),
                'customer_name' => $address->full_name,
                'customer_email' => $user->email,
                'customer_phone' => $address->phone,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_cost' => $shipping,
                'total' => $total,
                'payment_method' => $request->string('payment_method')->toString(),

                'payment_reference' => in_array(
                    $request->input('payment_method'),
                    ['bkash', 'nagad'],
                    true
                ) ? $request->validated('payment_reference') : null,

                'payment_payer_number' => in_array(
                    $request->input('payment_method'),
                    ['bkash', 'nagad'],
                    true
                ) ? $request->validated('payment_payer_number') : null,

                // All payments require verification or collection.
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'shipping_address' => $address->address_line,
                'shipping_area' => $address->area,
                'shipping_city' => $address->city,
                'shipping_district' => $address->district,
                'shipping_postal_code' => $address->postal_code,
                'customer_notes' => $request->input('customer_note'),
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => null,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->unit_price,
                ]);

                $product = $item->product;

                $quantitySold = (int) $item->quantity;
                $stockBefore = (int) $product->stock_quantity;
                $stockAfter = $stockBefore - $quantitySold;

                // Update the actual product stock.
                $product->decrement(
                    'stock_quantity',
                    $quantitySold
                );

                // Record the stock movement.
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'type' => 'sale',
                    'quantity' => -$quantitySold,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Stock reduced after order {$order->order_number}.",
                ]);
            }

            if ($coupon && $discount > 0) {
                $coupon->usages()->create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'discount_amount' => $discount,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        $request->session()->forget('applied_coupon');

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load('items');

        return view('checkout.success', compact('order'));
    }
}
