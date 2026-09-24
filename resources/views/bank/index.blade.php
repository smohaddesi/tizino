@extends('layouts.app')

@section('title', 'بانک سؤال')

@section('content')
    <div class="container py-4 py-lg-5">
        <h4 class="mb-4">بانک سؤال</h4>

        @if ($subjects->isEmpty())
            <div class="alert alert-info">درسی برای پایه‌ی تحصیلی شما ثبت نشده است.</div>
        @else
            <div class="row g-3">
                @foreach ($subjects as $subject)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('bank.subject', $subject) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-2 text-dark">{{ $subject->title }}</h6>
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        {{ \App\Support\JalaliDate::toPersianDigits($subject->questions_count) }} سؤال
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
