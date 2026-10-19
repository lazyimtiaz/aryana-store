<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function apply(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:100'],
        ]);

        $user = $request->user();

        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['coupon_code' => 'Your cart is empty.']);
        }

        $subtotal = (float) $cart->items->sum(
            fn ($item) => $item->quantity * $item->unit_price
        );

        $code = strtoupper(trim($validated['coupon_code']));

        $coupon = Coupon::query()
            ->whereRaw('UPPER(code) = ?', [$code])
            ->first();

        if (! $coupon) {
            return back()->withErrors([
                'coupon_code' => 'This coupon code is invalid.',
            ])->withInput();
        }

        $error = $coupon->validationErrorFor($user->id, $subtotal);

        if ($error !== null) {
            return back()->withErrors([
                'coupon_code' => $error,
            ])->withInput();
        }

        $discount = $coupon->calculateDiscount($subtotal);

        $request->session()->put('applied_coupon', [
            'code' => $coupon->code,
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', sprintf(
                'Coupon %s applied. You save ৳%s.',
                $coupon->code,
                number_format($discount, 2)
            ));
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->session()->forget('applied_coupon');

        return redirect()
            ->route('cart.index')
            ->with('success', 'Coupon removed.');
    }

    /**
     * Return an error message if the coupon cannot be used.
     */
    private function couponError(
        Coupon $coupon,
        int $userId,
        float $subtotal
    ): ?string {
        if (! $coupon->is_active) {
            return 'This coupon is no longer active.';
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return 'This coupon is not available yet.';
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return 'This coupon has expired.';
        }

        if (
            $coupon->minimum_order_amount !== null &&
            $subtotal < (float) $coupon->minimum_order_amount
        ) {
            return 'Your order does not meet the minimum spend for this coupon.';
        }

        if (
            $coupon->usage_limit !== null &&
            $coupon->usages()->count() >= $coupon->usage_limit
        ) {
            return 'This coupon has reached its usage limit.';
        }

        if ($coupon->usage_limit_per_user !== null) {
            $userUsageCount = $coupon->usages()
                ->where('user_id', $userId)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return 'You have already used this coupon the maximum number of times.';
            }
        }

        if (! in_array($coupon->type, ['fixed', 'percentage', 'percent'], true)) {
            return 'This coupon has an unsupported discount type.';
        }

        if ((float) $coupon->value <= 0) {
            return 'This coupon does not have a valid discount value.';
        }

        return null;
    }

    /**
     * Calculate a discount against the merchandise subtotal.
     */
    private function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if (in_array($coupon->type, ['percentage', 'percent'], true)) {
            $discount = $subtotal * ((float) $coupon->value / 100);
        } else {
            $discount = (float) $coupon->value;
        }

        if ($coupon->maximum_discount_amount !== null) {
            $discount = min(
                $discount,
                (float) $coupon->maximum_discount_amount
            );
        }

        return round(min($discount, $subtotal), 2);
    }
}
