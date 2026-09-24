@extends('layouts.app')

@section('title', 'داشبورد دانش‌آموز')

@push('styles')
    <style>
        .stat-card {
            border: none;
            border-radius: 1rem;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
        }

        .stat-card .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: .8rem;
            background: rgba(255, 255, 255, .22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .stat-card .stat-label {
            font-size: .82rem;
            opacity: .9;
        }

        .stat-card.bg-tz-blue { background: linear-gradient(135deg, #2f6fed, #1b4fc7); }
        .stat-card.bg-tz-orange { background: linear-gradient(135deg, #fd9843, #fd7e14); }
        .stat-card.bg-tz-purple { background: linear-gradient(135deg, #9b7cf6, #7c5cf5); }
        .stat-card.bg-tz-green { background: linear-gradient(135deg, #2fcf8e, #17a673); }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        <h4 class="mb-4">خوش آمدی، {{ auth()->user()->name }} 👋</h4>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card stat-card bg-tz-green h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
                        <div>
                            <div class="stat-value">
                                {{ $subscriptionDaysLeft !== null ? \App\Support\JalaliDate::toPersianDigits($subscriptionDaysLeft) : '—' }}
                            </div>
                            <div class="stat-label">روز اعتبار اشتراک</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card bg-tz-orange h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <div>
                            <div class="stat-value">{{ \App\Support\JalaliDate::toPersianDigits($exams->count()) }}</div>
                            <div class="stat-label">آزمون در دسترس</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card bg-tz-purple h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon"><i class="bi bi-trophy"></i></div>
                        <div>
                            <div class="stat-value">{{ \App\Support\JalaliDate::toPersianDigits($finishedAttemptsCount) }}</div>
                            <div class="stat-label">آزمون انجام‌شده</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card stat-card bg-tz-blue h-100">
                    <a href="{{ route('bank.index') }}" class="card-body d-flex align-items-center gap-3 text-decoration-none text-white">
                        <div class="stat-icon"><i class="bi bi-journal-bookmark"></i></div>
                        <div>
                            <div class="stat-value">{{ \App\Support\JalaliDate::toPersianDigits($bankQuestionsCount) }}</div>
                            <div class="stat-label">سؤال در بانک سؤال</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @if (! $activeSubscription)
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>شما در حال حاضر اشتراک فعالی ندارید.</span>
                <a href="{{ route('subscriptions.plans') }}" class="btn btn-sm btn-warning rounded-pill fw-bold">
                    تهیه‌ی اشتراک
                </a>
            </div>
        @endif

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
                                مدت زمان: {{ \App\Support\JalaliDate::toPersianDigits($exam->duration_minutes) }} دقیقه
                                | تعداد سؤال: {{ \App\Support\JalaliDate::toPersianDigits($exam->total_questions) }}
                                | نمره کل: {{ \App\Support\JalaliDate::toPersianDigits($exam->total_score) }}
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
@endsection
