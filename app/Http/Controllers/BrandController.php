<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $allBrands = Brand::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $featuredBrands = $allBrands
            ->where('is_featured', true)
            ->values();

        $brands = $allBrands
            ->groupBy(function ($brand) {
                return strtoupper(substr($brand->name, 0, 1));
            });

        return view('products.brands', [
            'brands' => $brands,
            'featuredBrands' => $featuredBrands,
        ]);
    }

    public function show(Brand $brand): View
    {
        abort_unless($brand->is_active, 404);

        $products = $brand->products()
            ->where('active', true)
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(12);

        return view('products.index', [
            'title' => $brand->name,
            'description' => $brand->description,
            'context' => $brand,
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
