<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q'));

        $products = Product::query()
            ->where('status', 'published')
            ->with(['brand', 'primaryImage'])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('brand', function ($brandQuery) use ($search) {
                            $brandQuery->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'title' => $search !== ''
                ? "Search results for \"{$search}\""
                : 'Search',
            'description' => 'Search our skincare and beauty collection.',
            'context' => 'search',
            'products' => $products,
            'brands' => collect(),
            'categories' => collect(),
        ]);
    }
}
