<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('product_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->enum('method', ['CREDIT_CARD', 'PIX', 'BOLETO'])->default('PIX');
            $table->integer('installments')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_payment');
    }
};
