@extends('layouts.app')

@section('title', $subject->title . ' - بانک سؤال')

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">بانک سؤال</a></li>
                <li class="breadcrumb-item active">{{ $subject->title }}</li>
            </ol>
        </nav>

        <h5 class="mb-4">{{ $subject->title }}</h5>

        @if ($topics->isEmpty())
            <div class="alert alert-info">موضوعی برای این درس ثبت نشده است.</div>
        @else
            <div class="list-group">
                @foreach ($topics as $topic)
                    <a href="{{ route('bank.topic', $topic) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        {{ $topic->title }}
                        <span class="badge bg-secondary rounded-pill">{{ \App\Support\JalaliDate::toPersianDigits($topic->questions_count) }} سؤال</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
