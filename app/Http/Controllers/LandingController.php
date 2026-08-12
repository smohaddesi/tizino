<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Question;

class LandingController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'questions' => Question::query()->where('is_active', true)->count(),
            'exams' => Exam::query()->where('is_active', true)->count(),
            'grades' => Grade::query()->count(),
        ];

        return view('landing', compact('stats'));
    }
}
