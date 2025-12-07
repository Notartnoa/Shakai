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
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('creator_id'); // pemilik product
            $table->unsignedBigInteger('buyer_id'); // pembeli
            $table->unsignedBigInteger('total_price');
            $table->boolean('is_paid')->default(false);
            $table->string('proof')->nullable(); // untuk simpan transaction_id dari midtrans
            $table->string('snap_token')->nullable(); // token dari midtrans
            $table->string('midtrans_order_id')->nullable(); // order id yang dikirim ke midtrans
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, etc
            $table->timestamp('paid_at')->nullable(); // kapan dibayar
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
