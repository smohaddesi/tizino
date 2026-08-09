<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>داشبورد دانش‌آموز</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">خوش آمدی، {{ auth()->user()->name }} 👋</h4>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">خروج</button>
            </form>
        </div>

        @if (! auth()->user()->grade_id)
            <div class="alert alert-warning">
                برای مشاهده‌ی آزمون‌های در دسترس، ابتدا باید پایه‌ی تحصیلی حسابت مشخص باشد.
                لطفاً با پشتیبانی تماس بگیر.
            </div>
        @elseif ($exams->isEmpty())
            <div class="alert alert-info">
                در حال حاضر آزمون فعالی برای پایه‌ی تحصیلی شما وجود ندارد.
            </div>
        @else
            <h6 class="mb-3">آزمون‌های در دسترس</h6>
            <div class="list-group">
                @foreach ($exams as $exam)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $exam->title }}</div>
                            <small class="text-muted">
                                مدت زمان: {{ $exam->duration_minutes }} دقیقه
                                | تعداد سؤال: {{ $exam->total_questions }}
                                | نمره کل: {{ $exam->total_score }}
                            </small>
                        </div>

                        @if ($exam->unfinishedAttempt)
                            <a href="{{ route('attempts.show', $exam->unfinishedAttempt) }}" class="btn btn-warning btn-sm">
                                ادامه‌ی آزمون
                            </a>
                        @elseif ($exam->finishedAttemptsCount >= $exam->max_attempts)
                            <a href="{{ route('attempts.result', $exam->lastFinishedAttempt) }}" class="btn btn-outline-secondary btn-sm">
                                مشاهده‌ی نتیجه
                            </a>
                        @else
                            <a href="{{ route('exams.start', $exam) }}" class="btn btn-primary btn-sm">
                                شروع آزمون
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
