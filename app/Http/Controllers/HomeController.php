<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $newArrivals = Product::query()
            ->where('status', 'published')
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->where('new_arrival', true)
            ->latest()
            ->take(4)
            ->get();

        $bestSellers = Product::query()
            ->where('status', 'published')
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->where('best_seller', true)
            ->take(4)
            ->get();

        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $brands = Brand::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        return view('home.index', [
            'newArrivals' => $newArrivals,
            'bestSellers' => $bestSellers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
