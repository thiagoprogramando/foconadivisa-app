<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('cascade');
            $table->string('title');
            $table->string('response')->nullable();
            $table->integer('type')->default(1); // 1 is Standard | 2 is Finance | 3 is Product | 4 is Questions | 5 is Awnswer | 6 is notebook
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('faq');
    }
};
