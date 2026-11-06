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
        Schema::create('jlpt_exam_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_no', 36)->unique();
            $table->integer('score'); // 0 - 180
            $table->integer('score_vocabulary'); // 0 - 60
            $table->integer('score_grammar'); // 0 - 60
            $table->integer('score_reading'); // 0 - 60
            $table->integer('total_questions')->default(30);
            $table->integer('correct_answers');
            $table->boolean('is_passed')->default(false);
            $table->string('grade', 10); // A, B, C, D
            $table->integer('time_taken'); // dalam detik
            $table->json('answers')->nullable(); // detail jawaban user
            $table->timestamps();

            $table->index(['user_id', 'is_passed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jlpt_exam_results');
    }
};

