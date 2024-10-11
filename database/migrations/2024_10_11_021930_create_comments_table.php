<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('comment_id')->nullable()->constrained('comments', 'id')->onDelete('cascade');
            $table->longText('comment');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('comments');
    }
};
