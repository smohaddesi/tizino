<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {

            $table->id();

            $table->foreignId('grade_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 200);

            $table->text('description')->nullable();

            $table->unsignedSmallInteger('duration_minutes');

            $table->unsignedSmallInteger('total_questions')->default(0);

            $table->unsignedSmallInteger('total_score')->default(0);

            $table->unsignedTinyInteger('max_attempts')->default(1);

            $table->timestamp('start_at')->nullable();

            $table->timestamp('end_at')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};