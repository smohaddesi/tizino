@extends('layouts.app')

@section('title', $topic->title . ' - بانک سؤال')

@push('styles')
    <style>
        .answer-option.is-correct.revealed {
            color: #198754;
            font-weight: 600;
        }

        .answer-option {
            margin-bottom: .35rem;
        }

        .answer-option .option-index {
            font-weight: 600;
            margin-left: .35rem;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">بانک سؤال</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bank.subject', $topic->subject) }}">{{ $topic->subject->title }}</a></li>
                <li class="breadcrumb-item active">{{ $topic->title }}</li>
            </ol>
        </nav>

        <h5 class="mb-4">{{ $topic->title }}</h5>

        @unless ($hasAccess)
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>برای مشاهده‌ی همه‌ی سؤالات این موضوع، اشتراک تهیه کن.</span>
                <a href="{{ route('subscriptions.plans') }}" class="btn btn-warning btn-sm rounded-pill fw-bold">
                    تهیه‌ی اشتراک
                </a>
            </div>
        @endunless

        <form method="GET" class="row g-2 mb-4">
            <div class="col-6 col-md-3">
                <select name="difficulty" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">همه‌ی سطوح</option>
                    @foreach (range(1, 5) as $level)
                        <option value="{{ $level }}" @selected(($filters['difficulty'] ?? '') == $level)>
                            سطح {{ \App\Support\JalaliDate::toPersianDigits($level) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 col-md-6">
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control form-control-sm" placeholder="جستجو در متن سؤال...">
            </div>
            <div class="col-4 col-md-3 d-grid">
                <button type="submit" class="btn btn-outline-primary btn-sm">اعمال فیلتر</button>
            </div>
        </form>

        @forelse ($questions as $question)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary">سطح سختی: {{ \App\Support\JalaliDate::toPersianDigits($question->difficulty) }}</span>
                        @if ($question->is_free_sample)
                            <span class="badge bg-success">نمونه‌ی رایگان</span>
                        @endif
                    </div>

                    @if ($question->is_locked)
                        <p class="mb-2 text-muted" style="filter: blur(3px); user-select: none;">
                            {{ \Illuminate\Support\Str::limit($question->body, 60) }}
                        </p>
                        <div class="alert alert-light border text-center mb-0">
                            🔒 این سؤال فقط برای مشترکین قابل مشاهده است
                            <a href="{{ route('subscriptions.plans') }}" class="btn btn-warning btn-sm rounded-pill fw-bold d-block mt-2 mx-auto" style="max-width: 220px;">
                                تهیه‌ی اشتراک
                            </a>
                        </div>
                    @else
                        <p class="mb-3">{{ $question->body }}</p>

                        <ul class="list-unstyled small mb-3">
                            @foreach ($question->options as $option)
                                <li class="answer-option {{ $option->is_correct ? 'is-correct' : '' }}" data-answer-group="q-{{ $question->id }}">
                                    <span class="option-index">{{ \App\Support\JalaliDate::toPersianDigits($loop->iteration) }}.</span>
                                    @if ($option->is_correct)
                                        <span class="answer-check d-none">✔</span>
                                    @endif
                                    {{ $option->body }}
                                </li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn btn-outline-secondary btn-sm mb-2 toggle-answer-btn" data-target="q-{{ $question->id }}">
                            نمایش پاسخ
                        </button>

                        @if ($question->answer_explanation)
                            <div class="answer-explanation d-none" data-answer-group="q-{{ $question->id }}">
                                <p class="text-muted small mt-2 mb-0">{{ $question->answer_explanation }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="alert alert-info">سؤالی با این فیلتر پیدا نشد.</div>
        @endforelse

        {{ $questions->links('pagination.bootstrap-5-fa') }}
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.toggle-answer-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const group = btn.dataset.target;
                    const options = document.querySelectorAll(`.answer-option[data-answer-group="${group}"]`);
                    const explanation = document.querySelector(`.answer-explanation[data-answer-group="${group}"]`);
                    const revealed = options.length > 0 && options[0].classList.contains('revealed');

                    options.forEach(opt => {
                        opt.classList.toggle('revealed');
                        const check = opt.querySelector('.answer-check');
                        if (check) {
                            check.classList.toggle('d-none');
                        }
                    });

                    if (explanation) {
                        explanation.classList.toggle('d-none');
                    }

                    btn.textContent = revealed ? 'نمایش پاسخ' : 'پنهان کردن پاسخ';
                });
            });
        </script>
    @endpush
@endsection
