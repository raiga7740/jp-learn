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
        Schema::create('user_vocabularies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('word_key', 64);
            $table->string('japanese', 191);
            $table->string('reading', 191)->nullable();
            $table->string('romaji', 191);
            $table->text('meaning');
            $table->string('category', 100)->default('Kosakata');
            $table->text('example_ja')->nullable();
            $table->text('example_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_favorite')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'word_key']);
            $table->index(['user_id', 'is_favorite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vocabularies');
    }
};

