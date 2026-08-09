<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * ساختار درس‌ها بر اساس ساختار واقعی آزمون تیزهوشان:
     * - پایه‌ی ششم به هفتم: فقط هوش/استعداد تحلیلی (بدون مباحث کتاب درسی).
     * - پایه‌ی نهم به دهم: ترکیبی از استعداد تحصیلی (کتاب درسی) و استعداد تحلیلی.
     */
    public function run(): void
    {
        $gradeSixth = Grade::where('title', 'ششم')->first();
        $gradeNinth = Grade::where('title', 'نهم')->first();

        if (! $gradeSixth || ! $gradeNinth) {
            $this->command?->warn('پایه‌های «ششم» و «نهم» یافت نشدند؛ ابتدا GradeSeeder را اجرا کنید.');

            return;
        }

        // حذف درس‌های نادرست قبلی برای پایه‌ی ششم
        // (چون آزمون ورودی ششم به هفتم شامل مباحث کتاب درسی نمی‌شود)
        Subject::where('grade_id', $gradeSixth->id)
            ->whereIn('title', ['ریاضی', 'علوم', 'فارسی', 'مطالعات اجتماعی', 'هوش و استعداد تحلیلی'])
            ->delete();

        $subjects = [
            // پایه ششم — فقط هوش و استعداد تحلیلی
            ['grade_id' => $gradeSixth->id, 'title' => 'هوش تصویری و فضایی', 'sort_order' => 1],
            ['grade_id' => $gradeSixth->id, 'title' => 'هوش کلامی و ادبی', 'sort_order' => 2],
            ['grade_id' => $gradeSixth->id, 'title' => 'هوش محاسباتی و منطقی', 'sort_order' => 3],
            ['grade_id' => $gradeSixth->id, 'title' => 'سرعت و دقت', 'sort_order' => 4],

            // پایه نهم — استعداد تحصیلی + استعداد تحلیلی
            ['grade_id' => $gradeNinth->id, 'title' => 'ریاضی', 'sort_order' => 1],
            ['grade_id' => $gradeNinth->id, 'title' => 'علوم', 'sort_order' => 2],
            ['grade_id' => $gradeNinth->id, 'title' => 'فارسی', 'sort_order' => 3],
            ['grade_id' => $gradeNinth->id, 'title' => 'مطالعات اجتماعی', 'sort_order' => 4],
            ['grade_id' => $gradeNinth->id, 'title' => 'هوش تصویری و فضایی', 'sort_order' => 5],
            ['grade_id' => $gradeNinth->id, 'title' => 'هوش کلامی و ادبی', 'sort_order' => 6],
            ['grade_id' => $gradeNinth->id, 'title' => 'هوش محاسباتی و منطقی', 'sort_order' => 7],
            ['grade_id' => $gradeNinth->id, 'title' => 'سرعت و دقت', 'sort_order' => 8],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['grade_id' => $subject['grade_id'], 'title' => $subject['title']],
                ['sort_order' => $subject['sort_order']]
            );
        }
    }
}
