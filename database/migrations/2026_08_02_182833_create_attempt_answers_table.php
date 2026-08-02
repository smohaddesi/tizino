<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('exam_attempt_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('exam_question_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('question_option_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('is_correct')
                ->nullable();

            $table->decimal('earned_score', 6, 2)
                ->default(0);

            $table->unsignedSmallInteger('time_spent')
                ->default(0);

            $table->timestamp('answered_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'exam_attempt_id',
                'exam_question_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};