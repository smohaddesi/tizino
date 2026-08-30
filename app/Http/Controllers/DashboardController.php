<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        $exams = Exam::query()
            ->where('grade_id', $user->grade_id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })
            ->orderBy('start_at')
            ->get();

        $exams->each(function ($exam) use ($user) {
            $exam->unfinishedAttempt = $exam->attempts()
                ->where('user_id', $user->id)
                ->where('is_finished', false)
                ->first();

            $exam->finishedAttemptsCount = $exam->attempts()
                ->where('user_id', $user->id)
                ->where('is_finished', true)
                ->count();

            $exam->lastFinishedAttempt = $exam->attempts()
                ->where('user_id', $user->id)
                ->where('is_finished', true)
                ->first();
        });

        return view('dashboard', [
            'exams' => $exams,
            'activeSubscription' => $user->activeSubscription(),
        ]);
    }
}
