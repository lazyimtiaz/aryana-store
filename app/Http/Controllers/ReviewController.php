<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {
        abort_unless($product->status === 'published', 404);

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ], [
            'rating.required' => 'Please select a rating.',
            'rating.integer' => 'Please select a valid rating.',
            'rating.min' => 'The minimum rating is 1 star.',
            'rating.max' => 'The maximum rating is 5 stars.',
            'title.max' => 'The review title cannot exceed 255 characters.',
            'comment.max' => 'The review cannot exceed 3000 characters.',
        ]);

        $alreadyReviewed = Review::query()
            ->where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()
                ->withErrors([
                    'review' => 'You have already submitted a review for this product.',
                ])
                ->withInput();
        }

        Review::query()->create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'is_approved' => false,
        ]);

        return redirect()
            ->to(route('products.show', $product) . '#reviews')
            ->with(
                'success',
                'Thank you for your review! It will appear once approved.'
            );
    }
}
