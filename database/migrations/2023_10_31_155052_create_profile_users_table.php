<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('id_card_number', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->enum('religion', ['Islam', 'Christian', 'Catholic', 'Hindu', 'Buddha'])->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_users');
    }
};
