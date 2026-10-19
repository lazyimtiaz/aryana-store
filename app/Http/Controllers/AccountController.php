<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $orders = auth()
            ->user()
            ->orders()
            ->latest()
            ->take(3)
            ->get();

        return view('profile.dashboard', compact('orders'));
    }

    public function orders(): View
    {
        $orders = auth()
            ->user()
            ->orders()
            ->latest()
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function order(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load('items.product.primaryImage');

        return view('profile.order', compact('order'));
    }

    public function addresses(): View
    {
        $addresses = auth()
            ->user()
            ->addresses()
            ->latest()
            ->get();

        return view('profile.addresses', compact('addresses'));
    }


}
