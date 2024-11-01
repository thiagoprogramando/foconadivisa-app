<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('payment_method');
            $table->string('payment_token');
            $table->string('payment_url')->nullable();
            $table->integer('payment_status')->default(0); // 0 is pendent 1 is approved
            $table->integer('quanty')->default(0);
            $table->integer('delivery')->default(0); // 0 is pendent 1 is approved
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale');
    }
};
