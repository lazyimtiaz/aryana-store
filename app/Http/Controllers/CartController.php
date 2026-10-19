<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->cart($request)
            ->load([
                'items.product.brand',
                'items.product.primaryImage',
            ]);

        $subtotal = (float) $cart->items->sum(
            fn ($item) => $item->quantity * $item->unit_price
        );

        $appliedCoupon = null;
        $couponDiscount = 0.0;

        $sessionCoupon = $request->session()->get('applied_coupon');

        if ($sessionCoupon && $cart->items->isNotEmpty()) {
            // Coupons are intended for authenticated customers.
            if (! $request->user()) {
                $request->session()->forget('applied_coupon');
            } else {
                $code = $sessionCoupon['code'] ?? null;

                $appliedCoupon = $code
                    ? Coupon::query()
                        ->where('code', $code)
                        ->first()
                    : null;

                if (! $appliedCoupon) {
                    $request->session()->forget('applied_coupon');
                } else {
                    $error = $appliedCoupon->validationErrorFor(
                        $request->user()->id,
                        $subtotal
                    );

                    if ($error !== null) {
                        $request->session()->forget('applied_coupon');
                        $appliedCoupon = null;
                    } else {
                        $couponDiscount = $appliedCoupon
                            ->calculateDiscount($subtotal);
                    }
                }
            }
        } elseif ($sessionCoupon) {
            // Do not retain a coupon on an empty cart.
            $request->session()->forget('applied_coupon');
        }

        return view('cart.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'appliedCoupon' => $appliedCoupon,
            'couponDiscount' => $couponDiscount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        $product = Product::query()
            ->where('status', 'published')
            ->findOrFail($validated['product_id']);

        abort_if(
            $product->stock_quantity < $quantity,
            422,
            'Requested quantity is not available.'
        );

        $cart = $this->cart($request);

        $item = $cart->items()->firstOrNew([
            'product_id' => $product->id,
        ]);

        $newQuantity = $item->exists
            ? $item->quantity + $quantity
            : $quantity;

        abort_if(
            $newQuantity > 10,
            422,
            'You can only add up to 10 units of a product.'
        );

        abort_if(
            $product->stock_quantity < $newQuantity,
            422,
            'Requested quantity is not available.'
        );

        $item->quantity = $newQuantity;
        $item->unit_price = $product->current_price;
        $item->save();

        return back()->with(
            'success',
            "{$product->name} was added to your bag."
        );
    }

    public function update(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $cart = $this->cart($request);

        abort_unless($cartItem->cart_id === $cart->id, 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $cartItem->load('product');

        abort_if(
            ! $cartItem->product ||
            $cartItem->product->stock_quantity < $validated['quantity'],
            422,
            'Requested quantity is not available.'
        );

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'unit_price' => $cartItem->product->current_price,
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $cart = $this->cart($request);

        abort_unless($cartItem->cart_id === $cart->id, 403);

        $cartItem->delete();

        return back()->with(
            'success',
            'Item removed from your bag.'
        );
    }

    private function cart(Request $request): Cart
    {
        $sessionId = $request->session()->get(
            'cart_session_id',
            fn () => (string) str()->uuid()
        );

        $request->session()->put('cart_session_id', $sessionId);

        return Cart::query()->firstOrCreate(
            auth()->check()
                ? ['user_id' => auth()->id()]
                : ['session_id' => $sessionId],
            ['session_id' => $sessionId]
        );
    }
}
