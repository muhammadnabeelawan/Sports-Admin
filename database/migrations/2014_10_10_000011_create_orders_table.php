<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_number')->unique();
            $table->float('total_amount');
            $table->float('discount')->default(0);
            $table->float('paid_amount')->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'shipped'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->enum('type', ['pos', 'website'])->default('pos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
