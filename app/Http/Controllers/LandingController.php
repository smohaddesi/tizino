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
            'questions' => $this->toPersianDigits(number_format(
                Question::query()->where('is_active', true)->count()
            )).'+',
            'exams' => $this->toPersianDigits(number_format(
                Exam::query()->where('is_active', true)->count()
            )),
            'grades' => $this->toPersianDigits(number_format(
                Grade::query()->count()
            )),
        ];

        return view('landing', compact('stats'));
    }

    private function toPersianDigits(string $number): string
    {
        return strtr($number, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }
}
