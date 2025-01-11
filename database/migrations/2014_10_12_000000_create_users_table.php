<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->longText('photo')->nullable();
            $table->string('name');
            $table->string('cpfcnpj')->nullable();
            $table->string('phone')->nullable();

            $table->integer('status')->default(0); // is 0 = pendent | 1 - active 3 - block
            $table->unsignedBigInteger('plan')->nullable();
            $table->foreign('plan')->references('id')->on('plan')->onDelete('cascade');
            $table->integer('type')->default(0); // is 0 = user | 1 - ADM 2 - Colaborador
            $table->unsignedBigInteger('meta')->default(100);

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');
            $table->string('code')->nullable();
            $table->string('customer')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
