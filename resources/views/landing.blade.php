<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تیزینو — تمرین آنلاین آزمون تیزهوشان</title>
    <meta name="description" content="تیزینو، سامانه‌ی تمرین آنلاین آزمون تیزهوشان با بانک سؤال طبقه‌بندی‌شده، آزمون‌های زمان‌دار و کارنامه‌ی دقیق برای پایه‌ی ششم و نهم.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #16213E;
            --ink-soft: #263659;
            --paper: #F2F4F1;
            --paper-line: #DCE3DB;
            --card: #FFFFFF;
            --gold: #C08829;
            --gold-soft: #F3E4C4;
            --green: #2E8B57;
            --green-soft: #E1F0E6;
            --text: #1C2333;
            --text-muted: #5B6472;
            --mono: ui-monospace, 'SFMono-Regular', Consolas, monospace;
            --radius-lg: 1.25rem;
            --radius-md: 0.85rem;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--paper);
            color: var(--text);
        }

        .tz-eyebrow {
            display: inline-flex;
            align-items: center;
            font-size: 0.8rem;
            color: var(--gold);
            font-weight: 600;
            background: var(--gold-soft);
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
        }
        .tz-eyebrow--on-dark {
            background: rgba(201, 146, 41, 0.18);
            color: #E4B15C;
        }

        /* ---------- Nav ---------- */
        .tz-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 1.1rem 0;
            flex-wrap: wrap;
        }
        .tz-logo {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--ink);
        }
        .tz-logo span { color: var(--gold); }
        .tz-nav-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-tz-login {
            color: var(--ink);
            border: 1px solid var(--paper-line);
            background: transparent;
            font-weight: 600;
        }
        .btn-tz-login:hover { border-color: var(--ink); color: var(--ink); }

        /* ---------- Hero ---------- */
        .tz-hero {
            background: var(--ink);
            color: #EDEFF4;
            border-radius: 0 0 2rem 2rem;
            padding: 3.25rem 0 4.5rem;
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
        .tz-hero::after {
            content: "";
            position: absolute;
            width: 26rem;
            height: 26rem;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(192,136,41,0.28), transparent 70%);
            top: -8rem;
            left: -6rem;
            filter: blur(10px);
            pointer-events: none;
        }
        .tz-hero-inner { position: relative; z-index: 1; }
        .tz-hero h1 {
            font-weight: 700;
            font-size: clamp(1.75rem, 4.2vw, 3rem);
            line-height: 1.4;
            margin-bottom: 1.1rem;
        }
        .tz-hero h1 em {
            font-style: normal;
            color: #F3D9A6;
            background: rgba(192, 136, 41, 0.22);
            padding: 0.05em 0.35em;
            border-radius: 0.4em;
            -webkit-box-decoration-break: clone;
            box-decoration-break: clone;
        }
        .tz-hero p.lead {
            color: #C6CCDC;
            font-size: 1.02rem;
            max-width: 34rem;
            margin-bottom: 1.85rem;
        }
        .tz-hero .btn-tz-primary {
            background: var(--gold);
            border: none;
            color: var(--ink);
            font-weight: 700;
            padding: 0.7rem 1.6rem;
            border-radius: 0.6rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        }
        .tz-hero .btn-tz-primary:hover {
            background: #d4a13e;
            color: var(--ink);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(192,136,41,0.55);
        }
        .tz-hero .btn-tz-ghost {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.35);
            color: #EDEFF4;
            font-weight: 600;
            padding: 0.7rem 1.6rem;
            border-radius: 0.6rem;
            transition: border-color 0.15s ease, transform 0.15s ease;
        }
        .tz-hero .btn-tz-ghost:hover { border-color: #fff; color: #fff; transform: translateY(-2px); }

        /* ---------- Sample question card ---------- */
        .tz-sample {
            background: var(--card);
            color: var(--text);
            border-radius: var(--radius-lg);
            padding: 1.6rem;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.45);
        }
        .tz-sample-tag {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.6rem;
        }
        .tz-sample-q {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.15rem;
        }
        .tz-bubble {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            text-align: right;
            background: var(--paper);
            border: 1.5px solid var(--paper-line);
            border-radius: var(--radius-md);
            padding: 0.6rem 0.85rem;
            margin-bottom: 0.55rem;
            cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
            font-family: inherit;
            font-size: 0.92rem;
            color: var(--text);
        }
        .tz-bubble:hover { border-color: var(--gold); }
        .tz-bubble .mark {
            flex: 0 0 1.55rem;
            height: 1.55rem;
            border-radius: 50%;
            border: 2px solid var(--paper-line);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--mono);
            font-size: 0.7rem;
            color: var(--text-muted);
        }
        .tz-bubble.is-selected .mark { border-color: var(--gold); color: var(--gold); }
        .tz-bubble.is-correct { background: var(--green-soft); border-color: var(--green); }
        .tz-bubble.is-correct .mark { background: var(--green); border-color: var(--green); color: #fff; }
        .tz-sample-feedback {
            font-size: 0.83rem;
            color: var(--green);
            font-weight: 600;
            min-height: 1.3rem;
            margin-top: 0.35rem;
        }

        /* ---------- Sections ---------- */
        .tz-section { padding: 4rem 0; }
        .tz-section-head { max-width: 38rem; margin-bottom: 2.5rem; }
        .tz-section-head h2 {
            font-weight: 700;
            font-size: clamp(1.4rem, 2.6vw, 1.9rem);
            color: var(--ink);
            margin-top: 0.4rem;
        }
        .tz-section-head p { color: var(--text-muted); margin: 0; }

        /* Audience cards */
        .tz-audience-card {
            background: var(--card);
            border: 1px solid var(--paper-line);
            border-radius: var(--radius-lg);
            padding: 1.6rem;
            height: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .tz-audience-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 34px -20px rgba(22,33,62,0.35);
        }
        .tz-audience-card .icon {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.65rem;
            background: var(--gold-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0.95rem;
        }
        .tz-audience-card h3 { font-weight: 700; font-size: 1.02rem; margin-bottom: 0.45rem; color: var(--ink); }
        .tz-audience-card p { color: var(--text-muted); font-size: 0.9rem; margin: 0; }

        /* Feature rows */
        .tz-feature { display: flex; gap: 1rem; padding: 1.35rem 0; border-top: 1px solid var(--paper-line); }
        .tz-feature:last-child { border-bottom: 1px solid var(--paper-line); }
        .tz-feature .num { color: var(--gold); font-weight: 700; font-size: 1.05rem; flex: 0 0 2.75rem; }
        .tz-feature h3 { font-weight: 700; font-size: 1rem; color: var(--ink); margin-bottom: 0.3rem; }
        .tz-feature p { color: var(--text-muted); margin: 0; font-size: 0.92rem; }

        /* Steps */
        .tz-steps { position: relative; }
        .tz-step { display: flex; gap: 1.1rem; padding-bottom: 2rem; position: relative; }
        .tz-step:last-child { padding-bottom: 0; }
        .tz-step::before {
            content: "";
            position: absolute;
            right: 1.3rem;
            top: 2.9rem;
            bottom: 0;
            width: 2px;
            background: var(--paper-line);
        }
        .tz-step:last-child::before { display: none; }
        .tz-step .badge-num {
            flex: 0 0 2.6rem;
            height: 2.6rem;
            border-radius: 50%;
            background: var(--ink);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.05rem;
            z-index: 1;
        }
        .tz-step h3 { font-weight: 700; color: var(--ink); margin-bottom: 0.25rem; font-size: 0.98rem; }
        .tz-step p { color: var(--text-muted); margin: 0; font-size: 0.92rem; }

        /* Stats */
        .tz-stats { background: var(--ink); border-radius: var(--radius-lg); padding: 2.75rem 1.75rem; color: #EDEFF4; }
        .tz-stat { text-align: center; padding: 0.5rem 0; }
        .tz-stat .value { font-weight: 700; font-size: 2.3rem; color: var(--gold); display: block; font-variant-numeric: tabular-nums; }
        .tz-stat .label { color: #C6CCDC; font-size: 0.87rem; margin-top: 0.3rem; }

        /* FAQ */
        .tz-faq .accordion-item {
            background: var(--card);
            border: 1px solid var(--paper-line);
            border-radius: var(--radius-md) !important;
            margin-bottom: 0.75rem;
            overflow: hidden;
        }
        .tz-faq .accordion-button {
            font-weight: 700;
            color: var(--ink);
            background: var(--card);
            font-size: 0.98rem;
            padding: 1.1rem 1.25rem;
        }
        .tz-faq .accordion-button:not(.collapsed) {
            color: var(--ink);
            background: var(--paper);
            box-shadow: none;
        }
        .tz-faq .accordion-button:focus { box-shadow: none; border-color: var(--paper-line); }
        .tz-faq .accordion-body { color: var(--text-muted); font-size: 0.92rem; padding: 0 1.25rem 1.1rem; }

        /* Final CTA */
        .tz-cta { text-align: center; padding: 3.5rem 0 4.5rem; }
        .tz-cta h2 { font-weight: 700; font-size: clamp(1.5rem, 3vw, 2.1rem); color: var(--ink); margin-bottom: 0.9rem; }
        .tz-cta p { color: var(--text-muted); margin-bottom: 1.6rem; }
        .btn-tz-cta { background: var(--ink); color: #fff; font-weight: 700; padding: 0.75rem 2rem; border-radius: 0.6rem; border: none; transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .btn-tz-cta:hover { background: var(--ink-soft); color: #fff; transform: translateY(-2px); box-shadow: 0 14px 26px -12px rgba(22,33,62,0.5); }

        /* Footer */
        .tz-footer {
            border-top: 1px solid var(--paper-line);
            padding: 1.5rem 0;
            color: var(--text-muted);
            font-size: 0.83rem;
        }
        .tz-footer a { color: var(--text-muted); }
        .tz-footer .row { row-gap: 0.5rem; }

        /* ---------- Mobile ---------- */
        @media (max-width: 767.98px) {
            .tz-hero { padding: 2.25rem 0 2.75rem; border-radius: 0 0 1.25rem 1.25rem; }
            .tz-hero p.lead { font-size: 0.95rem; }
            .tz-section { padding: 2.75rem 0; }
            .tz-section-head { margin-bottom: 1.85rem; }
            .tz-sample { margin-top: 2rem; padding: 1.35rem; }
            .tz-stats { padding: 2rem 1.25rem; }
            .tz-stat { padding: 0.75rem 0; }
            .tz-cta { padding: 3rem 0 3.5rem; }
            .tz-nav-actions { width: 100%; justify-content: flex-start; }
        }

        @media (prefers-reduced-motion: reduce) {
            .tz-bubble, .tz-audience-card { transition: none; }
        }
    </style>
</head>
<body>

    <div class="container">
        <nav class="tz-nav">
            <div class="tz-logo">تیز<span>ینو</span></div>
            <div class="tz-nav-actions">
                <a href="{{ route('login') }}" class="btn btn-tz-login btn-sm">ورود</a>
                <a href="{{ route('register') }}" class="btn btn-tz-primary btn-sm">ثبت‌نام رایگان</a>
            </div>
        </nav>
    </div>

    <section class="tz-hero">
        <div class="container tz-hero-inner">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="tz-eyebrow tz-eyebrow--on-dark">پایه‌ی ششم و نهم · آزمون تیزهوشان</div>
                    <h1>هر روز چند سؤال، تا <em>روز آزمون</em> آماده باشی</h1>
                    <p class="lead">
                        تیزینو یعنی تمرین واقعی: سؤال‌های طبقه‌بندی‌شده بر اساس درس و موضوع، آزمون با تایمر واقعی، و کارنامه‌ی دقیق بلافاصله بعد از هر آزمون.
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
                <p>یه سامانه، سه دیدگاه مختلف — هرکدوم دقیقاً همون چیزی که لازم دارن رو می‌بینن.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">📝</div>
                        <h3>دانش‌آموز</h3>
                        <p>تمرین کن، زمان بگیر، و ببین دقیقاً کجای درس‌ها قوی‌تری و کجا باید بیشتر کار کنی.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">👨‍👩‍👧</div>
                        <h3>والدین</h3>
                        <p>بعد از هر آزمون، کارنامه‌ی دقیق فرزندتون رو ببینید — بدون حدس زدن و بدون تماس مکرر با دبیر.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-audience-card">
                        <div class="icon">🎓</div>
                        <h3>دبیر</h3>
                        <p>سؤال‌های طبقه‌بندی‌شده رو به دانش‌آموزهاتون معرفی کنید، دقیقاً بر اساس ساختار واقعی آزمون.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" style="padding-top: 0;">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-5">
                    <div class="tz-section-head" style="margin-bottom: 0;">
                        <div class="tz-eyebrow">امکانات</div>
                        <h2>هرچی برای تمرین جدی لازمه</h2>
                        <p>نه یه لیست سؤال ساده — یه شبیه‌سازی واقعی از خود جلسه‌ی آزمون.</p>
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
                <div class="row g-3">
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ $stats['questions'] }}</span>
                        <span class="label">سؤال آماده</span>
                    </div>
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ $stats['exams'] }}</span>
                        <span class="label">آزمون فعال</span>
                    </div>
                    <div class="col-md-4 tz-stat">
                        <span class="value">{{ $stats['grades'] }}</span>
                        <span class="label">پایه‌ی تحصیلی پوشش داده‌شده</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section tz-faq" style="padding-top: 0; text-align: center;">
            <div class="tz-section-head" style="margin-inline: auto;">
                <div class="tz-eyebrow">سؤالات متداول</div>
                <h2>چیزهایی که معمولاً می‌پرسن</h2>
            </div>

            <div class="accordion text-start" id="tzFaqAccordion" style="max-width: 42rem; margin-inline: auto;">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tzFaq1">
                            آیا استفاده از تیزینو رایگانه؟
                        </button>
                    </h3>
                    <div id="tzFaq1" class="accordion-collapse collapse" data-bs-parent="#tzFaqAccordion">
                        <div class="accordion-body">بله. ثبت‌نام و تمرین توی تیزینو کاملاً رایگانه.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tzFaq2">
                            برای کدوم پایه‌هاست؟
                        </button>
                    </h3>
                    <div id="tzFaq2" class="accordion-collapse collapse" data-bs-parent="#tzFaqAccordion">
                        <div class="accordion-body">در حال حاضر برای پایه‌ی ششم و نهم — هر پایه با درس‌ها و موضوعات متناسب با آزمون واقعی خودش.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tzFaq3">
                            والدین چطور می‌تونن نتیجه‌ی فرزندشون رو ببینن؟
                        </button>
                    </h3>
                    <div id="tzFaq3" class="accordion-collapse collapse" data-bs-parent="#tzFaqAccordion">
                        <div class="accordion-body">فعلاً کارنامه‌ی هر آزمون از حساب کاربری همون دانش‌آموز در دسترسه. دسترسی جدا برای والدین به‌زودی اضافه می‌شه.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tzFaq4">
                            سؤال‌ها چقدر شبیه آزمون واقعی تیزهوشانه؟
                        </button>
                    </h3>
                    <div id="tzFaq4" class="accordion-collapse collapse" data-bs-parent="#tzFaqAccordion">
                        <div class="accordion-body">ساختار درس‌ها و موضوعات دقیقاً بر اساس ساختار واقعی آزمون تیزهوشان طراحی شده — نه یه لیست سؤال عمومی.</div>
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
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">© {{ date('Y') }} تیزینو</div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('login') }}">ورود دانش‌آموز</a>
                </div>
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
