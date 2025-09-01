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

            // Foreign Keys
            $table->foreignId('table_id')
                  ->constrained('tables')
                  ->cascadeOnDelete();

            $table->foreignId('coupon_id')
                  ->nullable()
                  ->constrained('coupons')
                  ->nullOnDelete();

            // Columns
            $table->enum('status', ['ordering', 'submitted', 'pending', 'ready'])
                  ->default('ordering'); // default as first step

            $table->decimal('total_price', 10, 2);
            $table->text('special_note')->nullable();
            $table->dateTime('order_time')->useCurrent();
            $table->dateTime('estimate_time')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
