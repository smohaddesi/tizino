<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\LandingSetting;
use App\Models\Question;
use App\Models\User;

class LandingController extends Controller
{
    public function __invoke()
    {
        $c = LandingSetting::content();

        foreach (['card_total', 'card_correct', 'card_wrong', 'card_time'] as $key) {
            $c[$key] = $this->fa((string) $c[$key]);
        }
        $c['card_percent'] = max(0, min(100, (int) $c['card_percent']));
        $c['card_percent_fa'] = $this->fa((string) $c['card_percent']);

        $c['stats'] = collect($c['stats'] ?? [])->map(function ($s) {
            $value = match ($s['source'] ?? 'manual') {
                'questions' => number_format(Question::query()->where('is_active', true)->count()).'+',
                'exams' => number_format(Exam::query()->where('is_active', true)->count()),
                'grades' => number_format(Grade::query()->count()),
                'users' => number_format(User::query()->count()),
                default => (string) ($s['value'] ?? ''),
            };
            $s['value'] = $this->fa($value);

            return $s;
        })->all();

        return view('landing', ['c' => $c]);
    }

    private function fa(string $number): string
    {
        return strtr($number, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }
}
