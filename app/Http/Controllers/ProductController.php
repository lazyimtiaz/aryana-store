<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()
            ->where('status', 'published')
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews');

        if (Auth::check()) {
            $wishlist = Auth::user()
                ->wishlist()
                ->with('items')
                ->first();

            $wishlistProductIds = $wishlist
                ? $wishlist->items->pluck('product_id')->all()
                : [];
        } else {
            $wishlistProductIds = [];
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($brandQuery) use ($request) {
                $brandQuery->where('slug', $request->brand);
            });
        }

        if ($request->filled('min_price')) {
            $query->where(function ($priceQuery) use ($request) {
                $priceQuery
                    ->where('sale_price', '>=', $request->min_price)
                    ->orWhere(function ($query) use ($request) {
                        $query->whereNull('sale_price')
                            ->where('regular_price', '>=', $request->min_price);
                    });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($priceQuery) use ($request) {
                $priceQuery
                    ->where('sale_price', '<=', $request->max_price)
                    ->orWhere(function ($query) use ($request) {
                        $query->whereNull('sale_price')
                            ->where('regular_price', '<=', $request->max_price);
                    });
            });
        }

        match ($request->input('sort')) {
            'price_low' => $query->orderByRaw(
                'COALESCE(sale_price, regular_price) ASC'
            ),

            'price_high' => $query->orderByRaw(
                'COALESCE(sale_price, regular_price) DESC'
            ),

            'newest' => $query->latest(),

            default => $query->latest(),
        };

        return view('products.index', [
            'title' => 'Shop',
            'description' => 'Explore our skincare collection.',
            'context' => null,
            'products' => $query->paginate(12)->withQueryString(),

            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),

            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'wishlistProductIds' => $wishlistProductIds,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'published', 404);

        $product->load([
            'brand',
            'categories',
            'images',
            'variants',
            'approvedReviews.user',
        ]);

        $product->loadAvg('approvedReviews', 'rating');
        $product->loadCount('approvedReviews');

        $relatedProducts = Product::query()
            ->where('status', 'published')
            ->whereKeyNot($product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                    ->orWhereHas('categories', function ($categoryQuery) use ($product) {
                        $categoryQuery->whereIn(
                            'categories.id',
                            $product->categories->pluck('id')
                        );
                    });
            })
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->take(4)
            ->get();

        $userReview = auth()->check()
            ? auth()->user()->reviews()
                ->where('product_id', $product->id)
                ->first()
            : null;

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'userReview' => $userReview,
        ]);
    }

    public function sale(): View
    {
        $products = Product::query()
            ->where('status', 'published')
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'regular_price')
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(12);

        return view('products.index', [
            'title' => 'Sale',
            'description' => 'Discover our current skincare offers.',
            'context' => 'sale',
            'products' => $products,

            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),

            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function newArrivals(): View
    {
        $products = Product::query()
            ->where('status', 'published')
            ->where('new_arrival', true)
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(12);

        return view('products.index', [
            'title' => 'New Arrivals',
            'description' => 'Discover the latest additions to our collection.',
            'context' => 'new-arrivals',
            'products' => $products,

            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),

            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function combos(): View
    {
        $products = Product::query()
            ->where('status', 'published')
            ->where('is_bundle', true)
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(12);

        return view('products.index', [
            'title' => 'Combos',
            'description' => 'Skincare bundles curated for your routine.',
            'context' => 'combos',
            'products' => $products,

            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),

            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
