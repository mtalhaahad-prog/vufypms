<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            $table->enum('role', ['student', 'supervisor', 'admin'])->default('student');
            $table->timestamps(); // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};