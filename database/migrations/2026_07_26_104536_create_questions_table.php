<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('topic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->longText('body');

            $table->string('image')->nullable();

            $table->unsignedTinyInteger('difficulty')
                ->default(2);

            $table->unsignedSmallInteger('answer_time')
                ->default(75);

            $table->string('source')->nullable();

            $table->longText('answer_explanation')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};