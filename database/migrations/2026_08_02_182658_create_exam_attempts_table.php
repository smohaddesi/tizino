<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('exam_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('started_at');

            $table->timestamp('submitted_at')
                ->nullable();

            $table->unsignedSmallInteger('correct_answers')
                ->default(0);

            $table->unsignedSmallInteger('wrong_answers')
                ->default(0);

            $table->unsignedSmallInteger('blank_answers')
                ->default(0);

            $table->decimal('score', 6, 2)
                ->default(0);

            $table->boolean('is_finished')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};