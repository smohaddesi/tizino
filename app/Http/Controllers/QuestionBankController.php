<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionBankController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user->grade_id, 403, 'پایه‌ی تحصیلی حساب شما مشخص نیست.');

        $subjects = Subject::query()
            ->where('grade_id', $user->grade_id)
            ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('bank.index', [
            'subjects' => $subjects,
        ]);
    }

    public function subject(Subject $subject): View
    {
        $user = Auth::user();

        abort_unless($subject->grade_id === $user->grade_id, 403);

        $topics = $subject->topics()
            ->withCount(['questions' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        return view('bank.subject', [
            'subject' => $subject,
            'topics' => $topics,
        ]);
    }

    public function topic(Topic $topic, Request $request): View
    {
        $user = Auth::user();
        $topic->loadMissing('subject');

        abort_unless($topic->subject->grade_id === $user->grade_id, 403);

        $hasAccess = $user->hasActiveSubscription();

        $query = $topic->questions()
            ->where('is_active', true)
            ->with(['options' => fn ($q) => $q->orderBy('sort_order')]);

        if ($request->filled('difficulty')) {
            $query->where('difficulty', (int) $request->input('difficulty'));
        }

        $search = trim((string) $request->input('q', ''));

        if ($search !== '') {
            $query->where('body', 'like', '%' . $search . '%');
        }

        $questions = $query->paginate(5)->withQueryString();

        $questions->getCollection()->transform(function (Question $question) use ($hasAccess) {
            $question->is_locked = ! $hasAccess && ! $question->is_free_sample;

            if ($question->is_locked) {
                $question->setRelation('options', collect());
            }

            return $question;
        });

        return view('bank.topic', [
            'topic' => $topic,
            'questions' => $questions,
            'hasAccess' => $hasAccess,
            'filters' => [
                'difficulty' => $request->input('difficulty'),
                'q' => $request->input('q'),
            ],
        ]);
    }
}
