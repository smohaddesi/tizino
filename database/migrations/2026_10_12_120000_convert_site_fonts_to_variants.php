<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_fonts', function (Blueprint $table) {
            $table->json('variants')->nullable()->after('family');
        });

        // ادغام ردیف‌های هم‌نام در یک ردیف با چند وزن
        $rows = DB::table('site_fonts')->orderBy('id')->get()->groupBy('family');

        foreach ($rows as $family => $group) {
            $variants = $group->map(fn ($r) => [
                'weight' => (int) $r->weight,
                'file_path' => $r->file_path,
            ])->values()->all();

            $keep = $group->first();

            DB::table('site_fonts')->where('id', $keep->id)->update([
                'variants' => json_encode($variants),
                'is_active' => $group->contains(fn ($r) => (bool) $r->is_active),
            ]);

            DB::table('site_fonts')
                ->where('family', $family)
                ->where('id', '!=', $keep->id)
                ->delete();
        }

        Schema::table('site_fonts', function (Blueprint $table) {
            $table->dropUnique(['family', 'weight']);
        });

        Schema::table('site_fonts', function (Blueprint $table) {
            $table->dropColumn(['weight', 'file_path', 'format']);
            $table->unique('family');
        });
    }

    public function down(): void
    {
        Schema::table('site_fonts', function (Blueprint $table) {
            $table->dropUnique(['family']);
            $table->unsignedSmallInteger('weight')->default(400);
            $table->string('file_path')->default('');
            $table->string('format', 20)->default('woff2');
            $table->dropColumn('variants');
            $table->unique(['family', 'weight']);
        });
    }
};
