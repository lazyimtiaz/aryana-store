<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $wishlist = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'items' => function ($query) {
                    $query->latest();
                },
                'items.product' => function ($query) {
                    $query
                        ->where('status', 'published')
                        ->with([
                            'brand',
                            'primaryImage',
                        ])
                        ->withAvg('approvedReviews', 'rating')
                        ->withCount('approvedReviews');
                },
            ])
            ->first();

        $items = $wishlist?->items
            ->filter(fn ($item) => $item->product !== null)
            ->values()
            ?? collect();

        return view('wishlist.index', [
            'items' => $items,
        ]);
    }

    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {
        abort_unless($product->status === 'published', 404);

        $wishlist = Wishlist::query()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        $wishlist->items()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return back()->with(
            'success',
            "{$product->name} was added to your wishlist."
        );
    }

    public function destroy(
        Request $request,
        Product $product
    ): RedirectResponse {
        $wishlist = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($wishlist) {
            $wishlist->items()
                ->where('product_id', $product->id)
                ->delete();
        }

        return back()->with(
            'success',
            "{$product->name} was removed from your wishlist."
        );
    }
}
