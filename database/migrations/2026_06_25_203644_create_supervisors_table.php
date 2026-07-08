<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id('supervisor_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('department', 100);
            $table->integer('max_teams')->default(5);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};