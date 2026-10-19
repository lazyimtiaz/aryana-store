<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();

            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            $table->decimal('regular_price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();

            $table->unsignedInteger('stock_quantity')->default(0);

            $table->enum('stock_status', [
                'in_stock',
                'out_of_stock',
                'pre_order',
            ])->default('in_stock');

            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            $table->boolean('featured')->default(false);
            $table->boolean('new_arrival')->default(false);
            $table->boolean('best_seller')->default(false);

            $table->text('ingredients')->nullable();
            $table->text('how_to_use')->nullable();

            $table->string('skin_type')->nullable();
            $table->string('skin_concerns')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
