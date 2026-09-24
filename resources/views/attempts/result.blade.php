@extends('layouts.app')

@section('title', 'نتیجه‌ی آزمون - ' . $exam->title)

@push('styles')
    <style>
        .review-row.correct {
            border-right: 4px solid #198754;
        }
        .review-row.wrong {
            border-right: 4px solid #dc3545;
        }
        .review-row.blank {
            border-right: 4px solid #adb5bd;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0">نتیجه‌ی آزمون: {{ $exam->title }}</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">بازگشت به داشبورد</a>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-muted small">نمره</div>
                        <div class="fs-4 fw-bold">{{ \App\Support\JalaliDate::toPersianDigits($attempt->score) }} / {{ \App\Support\JalaliDate::toPersianDigits($exam->total_score) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-muted small">پاسخ صحیح</div>
                        <div class="fs-4 fw-bold text-success">{{ \App\Support\JalaliDate::toPersianDigits($attempt->correct_answers) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-muted small">پاسخ غلط</div>
                        <div class="fs-4 fw-bold text-danger">{{ \App\Support\JalaliDate::toPersianDigits($attempt->wrong_answers) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-muted small">بدون پاسخ</div>
                        <div class="fs-4 fw-bold text-secondary">{{ \App\Support\JalaliDate::toPersianDigits($attempt->blank_answers) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h6 class="mb-3">مرور سؤالات</h6>

        @foreach ($answers as $answer)
            @php
                $status = $answer->question_option_id === null
                    ? 'blank'
                    : ($answer->is_correct ? 'correct' : 'wrong');
            @endphp
            <div class="card review-row {{ $status }} mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong>سؤال {{ \App\Support\JalaliDate::toPersianDigits($answer->examQuestion->question_number) }}</strong>
                        <span class="badge {{ $status === 'correct' ? 'bg-success' : ($status === 'wrong' ? 'bg-danger' : 'bg-secondary') }}">
                            @if ($status === 'correct') صحیح
                            @elseif ($status === 'wrong') غلط
                            @else بدون پاسخ
                            @endif
                        </span>
                    </div>
                    <p class="mt-2 mb-2">{{ $answer->examQuestion->question->body }}</p>

                    <ul class="list-unstyled mb-0 small">
                        @foreach ($answer->examQuestion->question->options as $option)
                            <li class="{{ $option->is_correct ? 'text-success fw-semibold' : '' }} {{ $option->id === $answer->question_option_id && ! $option->is_correct ? 'text-danger fw-semibold' : '' }}">
                                @if ($option->is_correct) ✔ @endif
                                @if ($option->id === $answer->question_option_id && ! $option->is_correct) ✘ (پاسخ شما) @endif
                                {{ $option->body }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection
