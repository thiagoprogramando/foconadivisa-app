<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 
    public function up(): void {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->nullable()->constrained('subjects', 'id')->onDelete('cascade');
            $table->integer('type')->default(1); // 1 is subject 2 is topic
            $table->string('name');
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('subjects');
    }
};
