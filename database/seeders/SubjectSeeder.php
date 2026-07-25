<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::insert([
            // پایه ششم
            ['grade_id' => 1, 'title' => 'ریاضی', 'sort_order' => 1],
            ['grade_id' => 1, 'title' => 'علوم', 'sort_order' => 2],
            ['grade_id' => 1, 'title' => 'فارسی', 'sort_order' => 3],
            ['grade_id' => 1, 'title' => 'مطالعات اجتماعی', 'sort_order' => 4],
            ['grade_id' => 1, 'title' => 'هوش و استعداد تحلیلی', 'sort_order' => 5],

            // پایه نهم
            ['grade_id' => 2, 'title' => 'ریاضی', 'sort_order' => 1],
            ['grade_id' => 2, 'title' => 'علوم', 'sort_order' => 2],
            ['grade_id' => 2, 'title' => 'فارسی', 'sort_order' => 3],
            ['grade_id' => 2, 'title' => 'مطالعات اجتماعی', 'sort_order' => 4],
            ['grade_id' => 2, 'title' => 'استعداد تحلیلی', 'sort_order' => 5],
        ]);
    }
}