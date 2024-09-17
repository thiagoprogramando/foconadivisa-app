<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('notebook_id')->nullable()->constrained('notebooks')->onDelete('cascade');
            $table->foreignId('notebook_question_id')->constrained('notebook_questions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('options')->onDelete('cascade');
            
            $table->integer('status')->default(0); // 0 - is not answer 1 - is correct 2 - is false
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('answers');
    }
};
