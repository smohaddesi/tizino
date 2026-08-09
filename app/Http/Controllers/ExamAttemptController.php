<?php

namespace App\Http\Controllers;

use App\Models\AttemptAnswer;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamAttemptController extends Controller
{
    /**
     * شروع آزمون جدید یا ادامه‌ی آزمون نیمه‌کاره‌ی موجود.
     */
    public function start(Exam $exam): RedirectResponse
    {
        $user = Auth::user();

        abort_if($exam->grade_id !== $user->grade_id, 403, 'این آزمون برای پایه‌ی تحصیلی شما نیست.');

        abort_unless($exam->is_active, 403, 'این آزمون در حال حاضر فعال نیست.');

        $now = now();
        if (($exam->start_at && $now->lt($exam->start_at)) || ($exam->end_at && $now->gt($exam->end_at))) {
            abort(403, 'این آزمون در حال حاضر در بازه‌ی فعال نیست.');
        }

        // اگه آزمون نیمه‌کاره‌ای وجود داره، همون رو ادامه بده
        $existing = ExamAttempt::query()
            ->where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('is_finished', false)
            ->first();

        if ($existing) {
            return redirect()->route('attempts.show', $existing);
        }

        $finishedAttemptsCount = ExamAttempt::query()
            ->where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('is_finished', true)
            ->count();

        abort_if(
            $finishedAttemptsCount >= $exam->max_attempts,
            403,
            'تعداد دفعات مجاز شرکت در این آزمون به پایان رسیده است.'
        );

        $examQuestions = $exam->examQuestions;

        abort_if($examQuestions->isEmpty(), 403, 'این آزمون هنوز سؤالی ندارد.');

        $attempt = DB::transaction(function () use ($exam, $user, $examQuestions) {
            $attempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'started_at' => now(),
                'is_finished' => false,
            ]);

            foreach ($examQuestions as $examQuestion) {
                AttemptAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'exam_question_id' => $examQuestion->id,
                    'question_option_id' => null,
                    'is_correct' => null,
                    'earned_score' => 0,
                ]);
            }

            return $attempt;
        });

        return redirect()->route('attempts.show', $attempt);
    }

    /**
     * صفحه‌ی اصلی گرفتن آزمون (پالت سؤالات + نمایش سؤال جاری).
     */
    public function show(ExamAttempt $attempt): View|RedirectResponse
    {
        $this->authorizeOwnership($attempt);

        if ($attempt->is_finished) {
            return redirect()->route('attempts.result', $attempt);
        }

        $deadline = $attempt->started_at->copy()->addMinutes($attempt->exam->duration_minutes);

        if (now()->greaterThanOrEqualTo($deadline)) {
            $this->finishAttempt($attempt);

            return redirect()->route('attempts.result', $attempt);
        }

        $examQuestions = $attempt->exam->examQuestions()
            ->with(['question.options'])
            ->get();

        $answers = $attempt->answers()
            ->get()
            ->keyBy('exam_question_id');

        return view('attempts.show', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
            'examQuestions' => $examQuestions,
            'answers' => $answers,
            'remainingSeconds' => now()->diffInSeconds($deadline),
        ]);
    }

    /**
     * ثبت/به‌روزرسانی پاسخ یک سؤال (AJAX، بدون رفرش صفحه).
     */
    public function answer(Request $request, ExamAttempt $attempt): JsonResponse
    {
        $this->authorizeOwnership($attempt);

        if ($attempt->is_finished) {
            return response()->json(['message' => 'این آزمون قبلاً پایان یافته است.'], 422);
        }

        $deadline = $attempt->started_at->copy()->addMinutes($attempt->exam->duration_minutes);
        if (now()->greaterThanOrEqualTo($deadline)) {
            $this->finishAttempt($attempt);

            return response()->json(['message' => 'زمان آزمون به پایان رسیده است.', 'finished' => true], 422);
        }

        $validated = $request->validate([
            'exam_question_id' => ['required', 'exists:exam_questions,id'],
            'question_option_id' => ['nullable', 'exists:question_options,id'],
        ]);

        $examQuestion = $attempt->exam->examQuestions()
            ->where('id', $validated['exam_question_id'])
            ->firstOrFail();

        $answer = AttemptAnswer::query()
            ->where('exam_attempt_id', $attempt->id)
            ->where('exam_question_id', $examQuestion->id)
            ->firstOrFail();

        if (empty($validated['question_option_id'])) {
            $answer->update([
                'question_option_id' => null,
                'is_correct' => null,
                'earned_score' => 0,
                'answered_at' => null,
            ]);

            return response()->json(['status' => 'cleared']);
        }

        $option = $examQuestion->question->options
            ->firstWhere('id', (int) $validated['question_option_id']);

        abort_if(! $option, 422, 'گزینه‌ی انتخاب‌شده معتبر نیست.');

        $isCorrect = (bool) $option->is_correct;

        $answer->update([
            'question_option_id' => $option->id,
            'is_correct' => $isCorrect,
            'earned_score' => $isCorrect ? $examQuestion->score : 0,
            'answered_at' => now(),
        ]);

        return response()->json(['status' => 'saved']);
    }

    /**
     * پایان دادن دستی به آزمون توسط دانش‌آموز.
     */
    public function finish(ExamAttempt $attempt): RedirectResponse
    {
        $this->authorizeOwnership($attempt);

        if (! $attempt->is_finished) {
            $this->finishAttempt($attempt);
        }

        return redirect()->route('attempts.result', $attempt);
    }

    /**
     * نمایش نتیجه‌ی نهایی آزمون.
     */
    public function result(ExamAttempt $attempt): View|RedirectResponse
    {
        $this->authorizeOwnership($attempt);

        if (! $attempt->is_finished) {
            return redirect()->route('attempts.show', $attempt);
        }

        $answers = $attempt->answers()
            ->with(['examQuestion.question.options', 'option'])
            ->get()
            ->sortBy(fn ($answer) => $answer->examQuestion->question_number);

        return view('attempts.result', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
            'answers' => $answers,
        ]);
    }

    private function authorizeOwnership(ExamAttempt $attempt): void
    {
        abort_if($attempt->user_id !== Auth::id(), 403);
    }

    private function finishAttempt(ExamAttempt $attempt): void
    {
        $answers = $attempt->answers;

        $correct = $answers->where('is_correct', true)->count();
        $wrong = $answers->where('is_correct', false)->count();
        $blank = $answers->whereNull('question_option_id')->count();
        $score = $answers->sum('earned_score');

        $attempt->update([
            'submitted_at' => now(),
            'is_finished' => true,
            'correct_answers' => $correct,
            'wrong_answers' => $wrong,
            'blank_answers' => $blank,
            'score' => $score,
        ]);
    }
}
