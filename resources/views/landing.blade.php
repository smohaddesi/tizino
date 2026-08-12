<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تیزینو — آماده‌سازی آزمون تیزهوشان</title>
    <meta name="description" content="تیزینو، سامانه‌ی تمرین آنلاین آزمون تیزهوشان با بانک سؤال طبقه‌بندی‌شده، آزمون‌های زمان‌دار و کارنامه‌ی دقیق برای پایه‌ی ششم و نهم.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #17213B;
            --ink-soft: #2B3A5C;
            --paper: #F3F5F2;
            --paper-line: #DCE3DB;
            --card: #FFFFFF;
            --gold: #C9922E;
            --gold-soft: #F3E4C4;
            --green: #2E8B57;
            --green-soft: #E1F0E6;
            --text: #1C2333;
            --text-muted: #5B6472;
            --mono: ui-monospace, 'SFMono-Regular', Consolas, monospace;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--paper);
            color: var(--text);
        }

        .tz-eyebrow {
            font-family: var(--mono);
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            color: var(--gold);
            text-transform: uppercase;
            font-weight: 600;
        }

        /* ---------- Nav ---------- */
        .tz-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 0;
        }
        .tz-logo {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--ink);
        }
        .tz-logo span { color: var(--gold); }
        .tz-nav-actions a { margin-inline-start: 0.5rem; }

        /* ---------- Hero ---------- */
        .tz-hero {
            background: var(--ink);
            color: #EDEFF4;
            border-radius: 0 0 2.5rem 2.5rem;
            padding: 3.5rem 0 5rem;
            position: relative;
            overflow: hidden;
        }
        .tz-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1.5px, transparent 1.5px);
            background-size: 22px 22px;
            opacity: 0.5;
            pointer-events: none;
        }
        .tz-hero-inner { position: relative; z-index: 1; }
        .tz-hero h1 {
            font-weight: 800;
            font-size: clamp(2rem, 4vw, 3.1rem);
            line-height: 1.35;
            margin-bottom: 1.1rem;
        }
        .tz-hero h1 em {
            font-style: normal;
            color: var(--gold);
        }
        .tz-hero p.lead {
            color: #C6CCDC;
            font-size: 1.05rem;
            max-width: 34rem;
            margin-bottom: 2rem;
        }
        .tz-hero .btn-tz-primary {
            background: var(--gold);
            border: none;
            color: var(--ink);
            font-weight: 700;
            padding: 0.7rem 1.6rem;
            border-radius: 0.6rem;
        }
        .tz-hero .btn-tz-primary:hover { background: #dba640; color: var(--ink); }
        .tz-hero .btn-tz-ghost {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.35);
            color: #EDEFF4;
            font-weight: 600;
            padding: 0.7rem 1.6rem;
            border-radius: 0.6rem;
        }
        .tz-hero .btn-tz-ghost:hover { border-color: #fff; color: #fff; }

        /* ---------- Sample question card (signature element) ---------- */
        .tz-sample {
            background: var(--card);
            color: var(--text);
            border-radius: 1.25rem;
            padding: 1.75rem;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.45);
        }
        .tz-sample-tag {
            font-family: var(--mono);
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
        }
        .tz-sample-q {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 1.25rem;
        }
        .tz-bubble {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            text-align: right;
            background: var(--paper);
            border: 1.5px solid var(--paper-line);
            border-radius: 0.75rem;
            padding: 0.65rem 0.9rem;
            margin-bottom: 0.6rem;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text);
        }
        .tz-bubble:hover { border-color: var(--gold); }
        .tz-bubble .mark {
            flex: 0 0 1.6rem;
            height: 1.6rem;
            border-radius: 50%;
            border: 2px solid var(--paper-line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--mono);
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .tz-bubble.is-selected .mark {
            border-color: var(--gold);
            color: var(--gold);
        }
        .tz-bubble.is-correct {
            background: var(--green-soft);
            border-color: var(--green);
        }
        .tz-bubble.is-correct .mark {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }
        .tz-sample-feedback {
            font-size: 0.85rem;
            color: var(--green);
            font-weight: 600;
            min-height: 1.3rem;
            margin-top: 0.4rem;
        }

        /* ---------- Sections ---------- */
        .tz-section { padding: 4.5rem 0; }
        .tz-section-head { max-width: 38rem; margin-bottom: 2.75rem; }
        .tz-section-head h2 {
            font-weight: 800;
            font-size: clamp(1.5rem, 2.6vw, 2rem);
            color: var(--ink);
            margin-top: 0.4rem;
        }
        .tz-section-head p { color: var(--text-muted); }

        /* Audience cards */
        .tz-audience-card {
            background: var(--card);
            border: 1px solid var(--paper-line);
            border-radius: 1rem;
            padding: 1.75rem;
            height: 100%;
        }
        .tz-audience-card .icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.7rem;
            background: var(--gold-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }
        .tz-audience-card h3 {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 0.5rem;
            color: var(--ink);
        }
        .tz-audience-card p { color: var(--text-muted); font-size: 0.92rem; margin: 0; }

        /* Feature rows */
        .tz-feature {
            display: flex;
            gap: 1.1rem;
            padding: 1.5rem 0;
            border-top: 1px solid var(--paper-line);
        }
        .tz-feature:last-child { border-bottom: 1px solid var(--paper-line); }
        .tz-feature .num {
            font-family: var(--mono);
            color: var(--gold);
            font-weight: 700;
            flex: 0 0 3rem;
        }
        .tz-feature h3 {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--ink);
            margin-bottom: 0.35rem;
        }
        .tz-feature p { color: var(--text-muted); margin: 0; font-size: 0.95rem; }

        /* Steps */
        .tz-steps { position: relative; }
        .tz-step {
            display: flex;
            gap: 1.25rem;
            padding-bottom: 2.25rem;
            position: relative;
        }
        .tz-step:last-child { padding-bottom: 0; }
        .tz-step::before {
            content: "";
            position: absolute;
            right: 1.35rem;
            top: 3rem;
            bottom: 0;
            width: 2px;
            background: var(--paper-line);
        }
        .tz-step:last-child::before { display: none; }
        .tz-step .badge-num {
            flex: 0 0 2.75rem;
            height: 2.75rem;
            border-radius: 50%;
            background: var(--ink);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--mono);
            font-weight: 700;
            z-index: 1;
        }
        .tz-step h3 { font-weight: 700; color: var(--ink); margin-bottom: 0.3rem; }
        .tz-step p { color: var(--text-muted); margin: 0; font-size: 0.95rem; }

        /* Stats */
        .tz-stats {
            background: var(--ink);
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            color: #EDEFF4;
        }
        .tz-stat { text-align: center; }
        .tz-stat .value {
            font-family: var(--mono);
            font-weight: 700;
            font-size: 2.4rem;
            color: var(--gold);
            display: block;
        }
        .tz-stat .label { color: #C6CCDC; font-size: 0.9rem; margin-top: 0.35rem; }

        /* Final CTA */
        .tz-cta {
            text-align: center;
            padding: 4rem 0 5rem;
        }
        .tz-cta h2 {
            font-weight: 800;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            color: var(--ink);
            margin-bottom: 1rem;
        }
        .tz-cta p { color: var(--text-muted); margin-bottom: 1.75rem; }
        .btn-tz-cta {
            background: var(--ink);
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 0.6rem;
            border: none;
        }
        .btn-tz-cta:hover { background: var(--ink-soft); color: #fff; }

        /* Footer */
        .tz-footer {
            border-top: 1px solid var(--paper-line);
            padding: 1.75rem 0;
            color: var(--text-muted);
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .tz-footer a { color: var(--text-muted); }

        @media (prefers-reduced-motion: reduce) {
            .tz-bubble { transition: none; }
        }
    </style>
</head>
<body>

    <div class="container">
        <nav class="tz-nav">
            <div class="tz-logo">تیز<span>ینو</span></div>
            <div class="tz-nav-actions">
                <a href="{{ route('login') }}" class="btn btn-tz-ghost btn-sm" style="color: var(--ink); border-color: var(--paper-line);">ورود</a>
                <a href="{{ route('register') }}" class="btn btn-tz-primary btn-sm">ثبت‌نام رایگان</a>
            </div>
        </nav>
    </div>

    <section class="tz-hero">
        <div class="container tz-hero-inner">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="tz-eyebrow" style="color:#DBA640;">پایه‌ی ششم و نهم · آزمون تیزهوشان</div>
                    <h1>سؤال به سؤال، برای <em>روز آزمون</em> آماده شو</h1>
                    <p class="lead">
                        تیزینو یه سامانه‌ی تمرینه با بانک سؤال طبقه‌بندی‌شده بر اساس درس و موضوع، آزمون‌های زمان‌دار شبیه‌سازی‌شده، و کارنامه‌ی دقیق بعد از هر آزمون.
                        برای دانش‌آموز، والدین و دبیر.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-tz-primary">شروع تمرین رایگان</a>
                        <a href="#how" class="btn btn-tz-ghost">چطور کار می‌کنه؟</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="tz-sample">
                        <div class="tz-sample-tag">نمونه سؤال · هوش کلامی</div>
                        <div class="tz-sample-q">کتاب : خواندن &nbsp;::&nbsp; قلم : ؟</div>

                        <button type="button" class="tz-bubble" data-option="پاک‌کن">
                            <span class="mark">A</span> پاک‌کن
                        </button>
                        <button type="button" class="tz-bubble" data-option="نوشتن" data-correct="true">
                            <span class="mark">B</span> نوشتن
                        </button>
                        <button type="button" class="tz-bubble" data-option="کاغذ">
                            <span class="mark">C</span> کاغذ
                        </button>
                        <button type="button" class="tz-bubble" data-option="رنگ">
                            <span class="mark">D</span> رنگ
                        </button>

                        <div class="tz-sample-feedback" id="tz-sample-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">

        <section class="tz-section">
            <div class="tz-section-head">
                <div class="tz-eyebrow">برای چه کسی</div>
                <h2>هرکسی که پای آزمون تیزهوشان وایستاده</h2>
                <p>تیزینو رو ساختیم تا هرکدوم از این سه نفر، همون چیزی رو ببینن که لازم دارن.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">📝</div>
                        <h3>دانش‌آموز</h3>
                        <p>تمرین کن، زمان بگیر، و ببین دقیقاً کجای درس‌ها قوی‌تر و کجا ضعیف‌تری.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">👨‍👩‍👧</div>
                        <h3>والدین</h3>
                        <p>بعد از هر آزمون، کارنامه‌ی دقیق فرزندتون رو ببینید — بدون حدس زدن.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">🎓</div>
                        <h3>دبیر</h3>
                        <p>سؤال‌های طبقه‌بندی‌شده رو معرفی کنید، دقیقاً بر اساس ساختار واقعی آزمون.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" style="padding-top: 0;">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="tz-section-head" style="margin-bottom: 0;">
                        <div class="tz-eyebrow">امکانات</div>
                        <h2>هرچی برای تمرین جدی لازمه</h2>
                        <p>نه یه لیست سؤال ساده — یه شبیه‌سازی واقعی از خود آزمون.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="tz-feature">
                        <div class="num">۰۱</div>
                        <div>
                            <h3>بانک سؤال طبقه‌بندی‌شده</h3>
                            <p>سؤال‌ها بر اساس پایه، درس و موضوع دسته‌بندی شدن — دقیقاً مطابق ساختار واقعی آزمون تیزهوشان.</p>
                        </div>
                    </div>
                    <div class="tz-feature">
                        <div class="num">۰۲</div>
                        <div>
                            <h3>آزمون زمان‌دار</h3>
                            <p>هر آزمون با تایمر واقعی برگزار می‌شه؛ همون فشار زمانی جلسه‌ی امتحان واقعی رو تجربه می‌کنی.</p>
                        </div>
                    </div>
                    <div class="tz-feature">
                        <div class="num">۰۳</div>
                        <div>
                            <h3>کارنامه و مرور پاسخ‌ها</h3>
                            <p>بلافاصله بعد از پایان آزمون، نمره و مرور سؤال‌به‌سؤال پاسخ‌ها رو می‌بینی — نه فقط یه عدد خام.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" id="how">
            <div class="tz-section-head">
                <div class="tz-eyebrow">نحوه‌ی شروع</div>
                <h2>سه قدم تا اولین آزمونت</h2>
            </div>
            <div class="tz-steps" style="max-width: 34rem;">
                <div class="tz-step">
                    <div class="badge-num">۱</div>
                    <div>
                        <h3>ثبت‌نام کن</h3>
                        <p>چند ثانیه کار داره — فقط نام، ایمیل و پایه‌ی تحصیلی.</p>
                    </div>
                </div>
                <div class="tz-step">
                    <div class="badge-num">۲</div>
                    <div>
                        <h3>آزمونت رو انتخاب کن</h3>
                        <p>بر اساس پایه‌ت، لیست آزمون‌های در دسترس رو توی داشبورد می‌بینی.</p>
                    </div>
                </div>
                <div class="tz-step">
                    <div class="badge-num">۳</div>
                    <div>
                        <h3>با تایمر واقعی امتحان بده</h3>
                        <p>بلافاصله بعد از پایان، کارنامه و مرور پاسخ‌ها آماده‌ست.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" style="padding-top: 0;">
            <div class="tz-stats">
                <div class="row g-4">
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ number_format($stats['questions']) }}+</span>
                        <span class="label">سؤال آماده</span>
                    </div>
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ number_format($stats['exams']) }}</span>
                        <span class="label">آزمون فعال</span>
                    </div>
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ number_format($stats['grades']) }}</span>
                        <span class="label">پایه‌ی تحصیلی پوشش داده‌شده</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-cta">
            <h2>همین امروز شروع کن</h2>
            <p>ثبت‌نام رایگانه و چند ثانیه بیشتر طول نمی‌کشه.</p>
            <a href="{{ route('register') }}" class="btn btn-tz-cta">ثبت‌نام رایگان</a>
        </section>

    </div>

    <footer class="tz-footer">
        <div class="container d-flex justify-content-between flex-wrap gap-2">
            <div>© {{ date('Y') }} تیزینو</div>
            <div>
                <a href="{{ route('login') }}">ورود دانش‌آموز</a>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            var bubbles = document.querySelectorAll('.tz-bubble');
            var feedback = document.getElementById('tz-sample-feedback');
            var answered = false;

            bubbles.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (answered) return;
                    answered = true;

                    bubbles.forEach(function (b) { b.classList.add('is-selected'); });
                    btn.classList.add('is-selected');

                    var correctBtn = document.querySelector('.tz-bubble[data-correct="true"]');
                    correctBtn.classList.add('is-correct');

                    if (btn.dataset.correct === 'true') {
                        feedback.textContent = 'آفرین! پاسخ درست: «نوشتن» — رابطه‌ی «ابزار و کاربردش».';
                    } else {
                        feedback.textContent = 'پاسخ درست «نوشتن» بود — رابطه‌ی «ابزار و کاربردش».';
                        feedback.style.color = 'var(--text-muted)';
                    }
                });
            });
        })();
    </script>

</body>
</html>
