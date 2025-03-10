<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('simulated_questions_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('simulated_questions')->onDelete('cascade');
            $table->integer('option_number');
            $table->longText('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('simulated_questions_options');
    }
};
