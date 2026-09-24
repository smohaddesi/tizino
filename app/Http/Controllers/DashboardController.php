<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Subject;
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

        $activeSubscription = $user->activeSubscription();

        $subscriptionDaysLeft = $activeSubscription
            ? (int) now()->diffInDays($activeSubscription->ends_at)
            : null;

        $finishedAttemptsCount = ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_finished', true)
            ->count();

        $bankQuestionsCount = $user->grade_id
            ? Subject::query()
                ->where('grade_id', $user->grade_id)
                ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
                ->get()
                ->sum('questions_count')
            : 0;

        return view('dashboard', [
            'exams' => $exams,
            'activeSubscription' => $activeSubscription,
            'subscriptionDaysLeft' => $subscriptionDaysLeft,
            'finishedAttemptsCount' => $finishedAttemptsCount,
            'bankQuestionsCount' => $bankQuestionsCount,
        ]);
    }
}
