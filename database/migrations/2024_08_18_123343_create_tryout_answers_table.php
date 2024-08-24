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
        Schema::create('tryout_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->contrained('tryouts')->cascadeOnDelete();
            $table->foreignId('question_id')->contrained('questions')->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->contrained('question_options')->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_answers');
    }
};
