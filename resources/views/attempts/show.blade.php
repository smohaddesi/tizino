<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $exam->title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .timer-bar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
        }
        .q-nav-btn {
            width: 42px;
            height: 42px;
        }
        .q-nav-btn.answered {
            background-color: #198754;
            color: #fff;
            border-color: #198754;
        }
        .q-nav-btn.current {
            outline: 2px solid #0d6efd;
        }
        .option-label {
            display: block;
            cursor: pointer;
            border: 1px solid #dee2e6;
            border-radius: .375rem;
            padding: .6rem 1rem;
            margin-bottom: .5rem;
        }
        .option-label:hover {
            background: #f8f9fa;
        }
        .option-label.selected {
            border-color: #0d6efd;
            background: #e7f1ff;
        }
    </style>
</head>
<body class="bg-light">

    <div class="timer-bar border-bottom py-2 px-3 d-flex justify-content-between align-items-center">
        <strong>{{ $exam->title }}</strong>
        <div>
            <span class="badge bg-dark fs-6" id="timer">--:--</span>
        </div>
    </div>

    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-9">
                <div id="questions-wrapper">
                    @foreach ($examQuestions as $index => $eq)
                        <div class="question-block card mb-2 {{ $index === 0 ? '' : 'd-none' }}"
                             data-question-index="{{ $index }}"
                             id="question-{{ $eq->id }}">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    سؤال {{ $eq->question_number }} از {{ $examQuestions->count() }}
                                    <span class="text-muted small">(نمره: {{ $eq->score }})</span>
                                </h6>
                                <p class="mb-3">{{ $eq->question->body }}</p>

                                <div class="options" data-exam-question-id="{{ $eq->id }}">
                                    @foreach ($eq->question->options as $option)
                                        @php
                                            $selected = optional($answers->get($eq->id))->question_option_id === $option->id;
                                        @endphp
                                        <label class="option-label {{ $selected ? 'selected' : '' }}"
                                               data-option-id="{{ $option->id }}">
                                            <input type="radio"
                                                   name="option-{{ $eq->id }}"
                                                   value="{{ $option->id }}"
                                                   class="form-check-input me-2"
                                                   @checked($selected)>
                                            {{ $option->body }}
                                        </label>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-prev">
                                        سؤال قبلی
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-next">
                                        سؤال بعدی
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-danger" id="finish-btn">
                        پایان آزمون
                    </button>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">سؤالات</h6>
                        <div class="d-flex flex-wrap gap-2" id="question-nav">
                            @foreach ($examQuestions as $index => $eq)
                                @php
                                    $isAnswered = optional($answers->get($eq->id))->question_option_id !== null;
                                @endphp
                                <button type="button"
                                        class="btn btn-outline-secondary q-nav-btn {{ $isAnswered ? 'answered' : '' }} {{ $index === 0 ? 'current' : '' }}"
                                        data-index="{{ $index }}"
                                        data-eq-id="{{ $eq->id }}">
                                    {{ $eq->question_number }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="finish-form" method="POST" action="{{ route('attempts.finish', $attempt) }}" class="d-none">
        @csrf
    </form>

    <script>
        const answerUrl = @json(route('attempts.answer', $attempt));
        const csrfToken = @json(csrf_token());
        let remainingSeconds = @json($remainingSeconds);

        const questionBlocks = document.querySelectorAll('.question-block');
        const navButtons = document.querySelectorAll('.q-nav-btn');
        let currentIndex = 0;

        function showQuestion(index) {
            questionBlocks.forEach(block => block.classList.add('d-none'));
            questionBlocks[index].classList.remove('d-none');

            navButtons.forEach(btn => btn.classList.remove('current'));
            navButtons[index].classList.add('current');

            currentIndex = index;
        }

        navButtons.forEach(btn => {
            btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.index)));
        });

        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentIndex < questionBlocks.length - 1) {
                    showQuestion(currentIndex + 1);
                }
            });
        });

        document.querySelectorAll('.btn-prev').forEach(btn => {
            btn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    showQuestion(currentIndex - 1);
                }
            });
        });

        document.querySelectorAll('.options').forEach(optionsBlock => {
            const examQuestionId = optionsBlock.dataset.examQuestionId;

            optionsBlock.querySelectorAll('.option-label').forEach(label => {
                label.addEventListener('click', () => {
                    const optionId = label.dataset.optionId;

                    optionsBlock.querySelectorAll('.option-label').forEach(l => l.classList.remove('selected'));
                    label.classList.add('selected');
                    label.querySelector('input[type=radio]').checked = true;

                    fetch(answerUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            exam_question_id: examQuestionId,
                            question_option_id: optionId,
                        }),
                    }).then(res => res.json()).then(data => {
                        if (data.finished) {
                            window.location.href = @json(route('attempts.result', $attempt));
                            return;
                        }

                        const navBtn = document.querySelector(`.q-nav-btn[data-eq-id="${examQuestionId}"]`);
                        if (navBtn) {
                            navBtn.classList.add('answered');
                        }
                    });
                });
            });
        });

        document.getElementById('finish-btn').addEventListener('click', () => {
            if (confirm('آیا از پایان دادن به آزمون مطمئن هستی؟ بعد از این دیگر نمی‌توانی پاسخ‌ها را تغییر دهی.')) {
                document.getElementById('finish-form').submit();
            }
        });

        function formatTime(totalSeconds) {
            const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
            const s = Math.floor(totalSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        const timerEl = document.getElementById('timer');
        timerEl.textContent = formatTime(remainingSeconds);

        const timerInterval = setInterval(() => {
            remainingSeconds -= 1;

            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                timerEl.textContent = '۰۰:۰۰';
                document.getElementById('finish-form').submit();
                return;
            }

            timerEl.textContent = formatTime(remainingSeconds);
        }, 1000);
    </script>
</body>
</html>
