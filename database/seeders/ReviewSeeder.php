<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'customer')->get();

        $products = Product::where('status', 'published')
            ->where('best_seller', true)
            ->take(8)
            ->get();

        $comments = [
            [
                'rating' => 5,
                'title' => 'Really good product',
                'comment' => 'The texture is lightweight and comfortable for everyday use.',
            ],
            [
                'rating' => 5,
                'title' => 'Would purchase again',
                'comment' => 'Very happy with the product and the overall experience.',
            ],
            [
                'rating' => 4,
                'title' => 'Good quality',
                'comment' => 'The product feels nice and works well in my routine.',
            ],
            [
                'rating' => 5,
                'title' => 'Loved it',
                'comment' => 'Packaging was good and the product feels great on the skin.',
            ],
        ];

        foreach ($products as $index => $product) {
            $user = $users[$index % $users->count()];
            $review = $comments[$index % count($comments)];

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => $review['rating'],
                'title' => $review['title'],
                'comment' => $review['comment'],
                'is_approved' => true,
            ]);
        }
    }
}
