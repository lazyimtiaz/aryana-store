<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'COSRX',
                'country' => 'South Korea',
                'description' => 'Korean skincare brand focused on practical, ingredient-conscious skincare.',
                'is_featured' => true,
            ],
            [
                'name' => 'Beauty of Joseon',
                'country' => 'South Korea',
                'description' => 'Modern Korean skincare inspired by traditional Korean beauty ingredients.',
                'is_featured' => true,
            ],
            [
                'name' => 'Anua',
                'country' => 'South Korea',
                'description' => 'Gentle skincare products focused on soothing and maintaining a healthy skin barrier.',
                'is_featured' => true,
            ],
            [
                'name' => 'SKIN1004',
                'country' => 'South Korea',
                'description' => 'Skincare centered around Madagascar Centella and calming formulations.',
                'is_featured' => true,
            ],
            [
                'name' => 'The Ordinary',
                'country' => 'Canada',
                'description' => 'Ingredient-focused skincare with straightforward formulations.',
                'is_featured' => true,
            ],
            [
                'name' => 'Purito Seoul',
                'country' => 'South Korea',
                'description' => 'Gentle skincare products designed around simple and soothing formulations.',
                'is_featured' => false,
            ],
            [
                'name' => 'Isntree',
                'country' => 'South Korea',
                'description' => 'Korean skincare brand offering hydration, soothing and skin barrier products.',
                'is_featured' => false,
            ],
            [
                'name' => 'Axis-Y',
                'country' => 'South Korea',
                'description' => 'Targeted skincare products designed for different skin concerns.',
                'is_featured' => false,
            ],
        ];

        foreach ($brands as $index => $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'logo' => 'brands/' . Str::slug($brand['name']) . '.webp',
                'banner' => 'brands/banners/' . Str::slug($brand['name']) . '.webp',
                'country' => $brand['country'],
                'is_featured' => $brand['is_featured'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
