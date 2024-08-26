<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->decimal('value', 10, 2)->default(0);
            $table->longText('payment_token');
            $table->longText('payment_url');
            $table->integer('payment_status')->default(0); // 0 is pendent 1 is approved
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoice');
    }
};
