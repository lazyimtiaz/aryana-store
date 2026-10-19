<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);
        $category->load([
            'children',
        ]);

        $categoryIds = collect([$category->id])
            ->merge($category->children->pluck('id'));

        $products = Product::query()
            ->where('status', 'published')
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('products.index', [
            'title' => $category->name,
            'description' => $category->description
                ?? "Explore {$category->name} products.",
            'context' => 'category',
            'category' => $category,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
