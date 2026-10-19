<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('order_number')->unique();

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');

            $table->string('order_status')->default('pending');

            $table->string('shipping_address');
            $table->string('shipping_area')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_district')->nullable();
            $table->string('shipping_postal_code')->nullable();

            $table->text('customer_notes')->nullable();

            $table->timestamp('placed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
