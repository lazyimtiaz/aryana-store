<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skincare',
                'children' => [
                    'Cleansers',
                    'Toners',
                    'Serums',
                    'Moisturizers',
                    'Sunscreen',
                    'Eye Care',
                    'Exfoliators',
                    'Masks',
                ],
            ],
            [
                'name' => 'Makeup',
                'children' => [
                    'Face',
                    'Eyes',
                    'Lips',
                ],
            ],
            [
                'name' => 'Hair Care',
                'children' => [
                    'Shampoo',
                    'Conditioner',
                    'Hair Treatment',
                ],
            ],
            [
                'name' => 'Body Care',
                'children' => [
                    'Body Wash',
                    'Body Lotion',
                    'Hand Care',
                ],
            ],
        ];

        foreach ($categories as $parentIndex => $categoryData) {
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => 'Explore our ' . strtolower($categoryData['name']) . ' collection.',
                'image' => 'categories/' . Str::slug($categoryData['name']) . '.webp',
                'is_featured' => $parentIndex < 2,
                'is_active' => true,
                'sort_order' => $parentIndex + 1,
            ]);

            foreach ($categoryData['children'] as $childIndex => $childName) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'description' => 'Shop our ' . strtolower($childName) . ' collection.',
                    'image' => 'categories/' . Str::slug($childName) . '.webp',
                    'is_featured' => false,
                    'is_active' => true,
                    'sort_order' => $childIndex + 1,
                ]);
            }
        }
    }
}
