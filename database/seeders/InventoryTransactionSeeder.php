<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventoryTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            InventoryTransaction::create([
                'product_id' => $product->id,
                'product_variant_id' => null,
                'type' => 'initial_stock',
                'quantity' => $product->stock_quantity,
                'stock_before' => 0,
                'stock_after' => $product->stock_quantity,
                'reference_type' => null,
                'reference_id' => null,
                'notes' => 'Initial demo inventory.',
            ]);
        }
    }
}
