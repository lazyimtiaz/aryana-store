<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_verified_at')->nullable();

            $table->foreignId('payment_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('payment_verification_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_verified_by');

            $table->dropColumn([
                'payment_verified_at',
                'payment_verification_notes',
            ]);
        });
    }
};
