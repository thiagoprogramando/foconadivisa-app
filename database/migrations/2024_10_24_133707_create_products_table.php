<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('file')->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->decimal('value', 10, 2)->default(0);
            $table->integer('status')->default(0); // 0 is pendent 1 is approved
            $table->integer('type')->default(1); // 1 is Digital 2 is Simulator
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
