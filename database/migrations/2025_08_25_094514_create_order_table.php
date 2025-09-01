<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id(); // PK: id auto-increment

            $table->unsignedBigInteger('customer_id')->nullable(); // FK to customers
            $table->unsignedBigInteger('table_id'); // FK to tables (not null)
            $table->unsignedBigInteger('coupon_id')->nullable(); // FK to coupons

            $table->enum('status', ['ordering', 'submitted', 'pending', 'ready'])->default('ordering');
            $table->decimal('total_price', 10, 2)->default(0.00);

            $table->text('special_note')->nullable(); // changed from timestamp to text (notes)
            $table->timestamp('order_time')->useCurrent();
            $table->timestamp('estimate_time')->nullable();

            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
