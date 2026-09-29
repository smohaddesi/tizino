<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('answer_time')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        DB::table('questions')->whereNull('answer_time')->update(['answer_time' => 75]);

        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('answer_time')->nullable(false)->default(75)->change();
        });
    }
};
