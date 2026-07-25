<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grade_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 100);

            $table->unsignedSmallInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->unique(['grade_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};