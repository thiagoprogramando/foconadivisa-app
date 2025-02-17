<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('simulated_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simulated_id')->nullable()->constrained('simulations')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->foreignId('jury_id')->nullable()->constrained('juries');
            $table->longText('question_text')->nullable();
            $table->longText('comment_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('simulated_questions');
    }
};
