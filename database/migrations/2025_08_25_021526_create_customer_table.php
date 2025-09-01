<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 255 is default
            $table->string('phone_number')->unique(); // phone should be unique
            $table->string('email')->unique()->nullable(); // email optional, unique if present
            $table->string('telegram_url')->nullable(); // use string for links instead of int
            $table->enum('status', ['active', 'inactive'])->default('active'); // matches your ERD
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
