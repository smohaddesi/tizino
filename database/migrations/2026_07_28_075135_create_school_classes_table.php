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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();

            // پایه
            $table->foreignId('grade_id')
                ->constrained()
                ->cascadeOnDelete();

            // عنوان کلاس (مثلاً: الف، ب، 101)
            $table->string('title');

            // سال تحصیلی
            $table->string('academic_year')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};