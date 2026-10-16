<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_fonts', function (Blueprint $table) {
            $table->id();
            $table->string('family');
            $table->unsignedSmallInteger('weight')->default(400);
            $table->string('file_path');
            $table->string('format', 20);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['family', 'weight']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_fonts');
    }
};
