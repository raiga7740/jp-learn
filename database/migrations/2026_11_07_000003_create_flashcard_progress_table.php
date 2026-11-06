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
        Schema::create('flashcard_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->string('card_key', 64);
            $table->unsignedTinyInteger('box')->default(1); // 1 = Belajar, 2 = Mengingat, 3 = Mahir, 4 = Mastered
            $table->unsignedSmallInteger('review_count')->default(0);
            $table->unsignedSmallInteger('fail_count')->default(0);
            $table->dateTime('next_review_at')->index();
            $table->dateTime('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'material_id', 'card_key']);
            $table->index(['user_id', 'material_id', 'box']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcard_progress');
    }
};

