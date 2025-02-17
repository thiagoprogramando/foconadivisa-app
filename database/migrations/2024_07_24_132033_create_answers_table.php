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

            $table->unsignedBigInteger('notebook_id')->nullable();
            $table->unsignedBigInteger('notebook_question_id')->nullable();
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id');
            
            $table->integer('status')->default(0); // 0 - is not answer 1 - is correct 2 - is false
            $table->integer('position')->default(1); // 1 - is not answer 2 - is answer and eliminate filter
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('answers');
    }
};
