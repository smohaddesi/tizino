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
            --brand: #F2793A;
            --brand-dark: #D35F22;
            --brand-soft: #FDEAD9;
            --green: #2FA36B;
            --green-soft: #E1F5EA;
            --blue: #2E6FE0;
            --blue-soft: #E4EBFC;
            --pink: #E0567E;
            --pink-soft: #FBE4EC;
            --paper: #F8F7F5;
            --card: #FFFFFF;
            --ink: #22262B;
            --text-muted: #6B7280;
            --line: #E9E5DF;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--paper);
            color: var(--ink);
        }

        .btn-pill {
            border-radius: 999px;
            font-weight: 700;
            padding: 0.65rem 1.5rem;
            border: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-pill:hover { transform: translateY(-2px); }

        .btn-brand { background: var(--brand); color: #fff; }
        .btn-brand:hover { background: var(--brand-dark); color: #fff; box-shadow: 0 12px 24px -10px rgba(242,121,58,0.5); }

        .btn-white { background: #fff; color: var(--brand-dark); }
        .btn-white:hover { color: var(--brand-dark); box-shadow: 0 10px 20px -10px rgba(0,0,0,0.25); }

        .btn-ghost-light { background: rgba(255,255,255,0.15); color: #fff; border: 1.5px solid rgba(255,255,255,0.5); }
        .btn-ghost-light:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ---------- Nav ---------- */
        .tz-nav {
            background: var(--brand);
        }
        .tz-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .tz-logo {
            font-weight: 800;
            font-size: 1.35rem;
            color: #fff;
        }
        .tz-logo span { color: #FFE1C4; }
        .tz-nav-links {
            display: flex;
            gap: 1.5rem;
        }
        .tz-nav-links a {
            color: #fff;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            opacity: 0.9;
        }
        .tz-nav-links a:hover { opacity: 1; }
        .tz-nav-actions { display: flex; gap: 0.5rem; }

        /* ---------- Hero ---------- */
        .tz-hero { background: var(--brand); padding: 2.5rem 0 4rem; }
        .tz-hero h1 {
            font-weight: 800;
            font-size: clamp(1.75rem, 4.2vw, 2.9rem);
            line-height: 1.4;
            color: #fff;
            margin-bottom: 1.1rem;
        }
        .tz-hero h1 em {
            font-style: normal;
            color: var(--ink);
            background: #FFE1C4;
            padding: 0.05em 0.4em;
            border-radius: 0.4em;
            -webkit-box-decoration-break: clone;
            box-decoration-break: clone;
        }
        .tz-hero p.lead { color: #FFEEDD; font-size: 1.02rem; max-width: 32rem; margin-bottom: 1.85rem; }

        .tz-eyebrow {
            display: inline-flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 700;
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.3rem 0.9rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        .tz-sample {
            background: var(--card);
            border-radius: 1.75rem;
            padding: 1.75rem;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.35);
        }
        .tz-sample-tag { font-size: 0.78rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.6rem; }
        .tz-sample-q { font-weight: 800; font-size: 1.15rem; margin-bottom: 1.15rem; }
        .tz-bubble {
            display: flex; align-items: center; gap: 0.75rem; width: 100%; text-align: right;
            background: var(--paper); border: 1.5px solid var(--line); border-radius: 999px;
            padding: 0.65rem 1.1rem; margin-bottom: 0.6rem; cursor: pointer;
            transition: border-color 0.15s ease, background 0.15s ease;
            font-family: inherit; font-size: 0.95rem; color: var(--ink);
        }
        .tz-bubble:hover { border-color: var(--brand); }
        .tz-bubble .mark {
            flex: 0 0 1.6rem; height: 1.6rem; border-radius: 50%; border: 2px solid var(--line);
            display: flex; align-items: center; justify-content: center; font-size: 0.72rem; color: var(--text-muted);
        }
        .tz-bubble.is-selected .mark { border-color: var(--brand); color: var(--brand); }
        .tz-bubble.is-correct { background: var(--green-soft); border-color: var(--green); }
        .tz-bubble.is-correct .mark { background: var(--green); border-color: var(--green); color: #fff; }
        .tz-sample-feedback { font-size: 0.85rem; color: var(--green); font-weight: 700; min-height: 1.3rem; margin-top: 0.5rem; padding: 0 0.5rem; }

        /* ---------- Sections ---------- */
        .tz-section { padding: 4.5rem 0; }
        .tz-section-head { max-width: 38rem; margin: 0 auto 2.75rem; text-align: center; }
        .tz-section-head h2 { font-weight: 800; font-size: clamp(1.5rem, 2.6vw, 2rem); color: var(--ink); margin-top: 0.5rem; }
        .tz-section-head p { color: var(--text-muted); margin: 0; }

        /* Cards (audience + features) — ino-style rounded card with colorful pill CTA */
        .tz-card {
            background: var(--card);
            border-radius: 1.75rem;
            padding: 1.9rem;
            height: 100%;
            box-shadow: 0 20px 40px -28px rgba(34,38,43,0.25);
            display: flex;
            flex-direction: column;
        }
        .tz-card .icon {
            width: 3rem; height: 3rem; border-radius: 1rem; display: flex; align-items: center;
            justify-content: center; font-size: 1.4rem; margin-bottom: 1.1rem;
        }
        .tz-card.tone-orange .icon { background: var(--brand-soft); }
        .tz-card.tone-green .icon { background: var(--green-soft); }
        .tz-card.tone-blue .icon { background: var(--blue-soft); }
        .tz-card.tone-pink .icon { background: var(--pink-soft); }

        .tz-card h3 { font-weight: 800; font-size: 1.1rem; margin-bottom: 0.5rem; color: var(--ink); }
        .tz-card p { color: var(--text-muted); font-size: 0.92rem; margin-bottom: 1.25rem; flex-grow: 1; }
        .tz-card .tag {
            align-self: flex-start;
            font-size: 0.78rem; font-weight: 700; padding: 0.35rem 0.9rem; border-radius: 999px;
        }
        .tz-card.tone-orange .tag { background: var(--brand-soft); color: var(--brand-dark); }
        .tz-card.tone-green .tag { background: var(--green-soft); color: #1f7a4d; }
        .tz-card.tone-blue .tag { background: var(--blue-soft); color: #1d4fb0; }
        .tz-card.tone-pink .tag { background: var(--pink-soft); color: #b8365a; }

        /* Steps */
        .tz-steps { position: relative; max-width: 34rem; margin: 0 auto; }
        .tz-step { display: flex; gap: 1.1rem; padding-bottom: 2rem; position: relative; }
        .tz-step:last-child { padding-bottom: 0; }
        .tz-step::before {
            content: ""; position: absolute; right: 1.3rem; top: 2.9rem; bottom: 0; width: 2px; background: var(--line);
        }
        .tz-step:last-child::before { display: none; }
        .tz-step .badge-num {
            flex: 0 0 2.6rem; height: 2.6rem; border-radius: 50%; background: var(--brand); color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: 800; z-index: 1;
        }
        .tz-step h3 { font-weight: 700; color: var(--ink); margin-bottom: 0.25rem; font-size: 0.98rem; text-align: right; }
        .tz-step p { color: var(--text-muted); margin: 0; font-size: 0.92rem; text-align: right; }

        /* Stats banner — ino-style solid color banner */
        .tz-stats { background: var(--brand); border-radius: 1.75rem; padding: 2.75rem 1.75rem; color: #fff; }
        .tz-stat { text-align: center; padding: 0.5rem 0; }
        .tz-stat .value { font-weight: 800; font-size: 2.3rem; color: #fff; display: block; }
        .tz-stat .label { color: #FFEEDD; font-size: 0.88rem; margin-top: 0.3rem; }

        /* FAQ — pill rows with circular chevron, ino-style */
        .tz-faq .accordion-item {
            background: var(--card);
            border: none;
            border-radius: 999px !important;
            margin-bottom: 0.75rem;
            overflow: hidden;
            box-shadow: 0 10px 24px -18px rgba(34,38,43,0.3);
        }
        .tz-faq .accordion-item.is-open { border-radius: 1.5rem !important; }
        .tz-faq .accordion-button {
            font-weight: 700;
            color: var(--ink);
            background: var(--card);
            font-size: 0.98rem;
            padding: 1rem 1.5rem;
        }
        .tz-faq .accordion-button::after {
            width: 1.9rem;
            height: 1.9rem;
            border-radius: 50%;
            background-color: var(--brand-soft);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23D35F22' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 6l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 0.85rem;
            flex-shrink: 0;
        }
        .tz-faq .accordion-button:not(.collapsed)::after {
            background-color: var(--brand);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 6l4 4 4-4'/%3E%3C/svg%3E");
        }
        .tz-faq .accordion-button:not(.collapsed) {
            color: var(--ink);
            background: var(--card);
            box-shadow: none;
        }
        .tz-faq .accordion-button:focus { box-shadow: none; }
        .tz-faq .accordion-body { color: var(--text-muted); font-size: 0.92rem; padding: 0 1.5rem 1.25rem; }

        /* Final CTA banner */
        .tz-cta-banner {
            background: var(--brand);
            border-radius: 1.75rem;
            padding: 3rem 2rem;
            text-align: center;
            color: #fff;
        }
        .tz-cta-banner h2 { font-weight: 800; font-size: clamp(1.5rem, 3vw, 2.1rem); margin-bottom: 0.9rem; }
        .tz-cta-banner p { color: #FFEEDD; margin-bottom: 1.6rem; }

        /* Footer */
        .tz-footer { background: var(--brand-dark); color: #FFEEDD; padding: 3rem 0 1.5rem; }
        .tz-footer h4 { color: #fff; font-weight: 700; font-size: 0.95rem; margin-bottom: 1rem; }
        .tz-footer a { color: #FFEEDD; text-decoration: none; font-size: 0.88rem; display: block; margin-bottom: 0.55rem; }
        .tz-footer a:hover { color: #fff; }
        .tz-footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 2rem; padding-top: 1.25rem; font-size: 0.82rem; color: #FFD9B3;
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;
        }

        /* ---------- Mobile ---------- */
        @media (max-width: 767.98px) {
            .tz-hero { padding: 2rem 0 2.75rem; }
            .tz-section { padding: 3rem 0; }
            .tz-section-head { margin-bottom: 1.85rem; }
            .tz-sample { margin-top: 2rem; padding: 1.35rem; }
            .tz-stats { padding: 2rem 1.25rem; }
            .tz-cta-banner { padding: 2.25rem 1.5rem; }
            .tz-nav-links { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .tz-bubble, .btn-pill { transition: none; }
        }
    </style>
</head>
<body>

    <nav class="tz-nav">
        <div class="container tz-nav-inner">
            <div class="tz-logo">تیز<span>ینو</span></div>
            <div class="tz-nav-links">
                <a href="#features">امکانات</a>
                <a href="#how">نحوه‌ی شروع</a>
                <a href="#faq">سؤالات متداول</a>
            </div>
            <div class="tz-nav-actions">
                <a href="{{ route('login') }}" class="btn-pill btn-ghost-light">ورود</a>
                <a href="{{ route('register') }}" class="btn-pill btn-white">ثبت‌نام رایگان</a>
            </div>
        </div>
    </nav>

    <section class="tz-hero">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="tz-eyebrow">پایه‌ی ششم و نهم · آزمون تیزهوشان</div>
                    <h1>هر روز چند سؤال، تا <em>روز آزمون</em> آماده باشی</h1>
                    <p class="lead">
                        بانک سؤال طبقه‌بندی‌شده بر اساس درس و موضوع، آزمون با تایمر واقعی، و کارنامه‌ی دقیق بلافاصله بعد از هر آزمون.
                        برای دانش‌آموز، والدین و دبیر.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('register') }}" class="btn-pill btn-white">شروع تمرین رایگان</a>
                        <a href="#how" class="btn-pill btn-ghost-light">چطور کار می‌کنه؟</a>
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
                <div class="tz-eyebrow" style="background: var(--brand-soft); color: var(--brand-dark);">برای چه کسی</div>
                <h2>هرکسی که پای آزمون تیزهوشان وایستاده</h2>
                <p>یه سامانه، سه دیدگاه مختلف — هرکدوم دقیقاً همون چیزی که لازم دارن رو می‌بینن.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="tz-card tone-orange">
                        <div class="icon">📝</div>
                        <h3>دانش‌آموز</h3>
                        <p>تمرین کن، زمان بگیر، و ببین دقیقاً کجای درس‌ها قوی‌تری و کجا باید بیشتر کار کنی.</p>
                        <span class="tag">شروع رایگان</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-card tone-green">
                        <div class="icon">👨‍👩‍👧</div>
                        <h3>والدین</h3>
                        <p>بعد از هر آزمون، کارنامه‌ی دقیق فرزندتون رو ببینید — بدون حدس زدن و بدون تماس مکرر با دبیر.</p>
                        <span class="tag">پیگیری پیشرفت</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-card tone-blue">
                        <div class="icon">🎓</div>
                        <h3>دبیر</h3>
                        <p>سؤال‌های طبقه‌بندی‌شده رو به دانش‌آموزهاتون معرفی کنید، دقیقاً بر اساس ساختار واقعی آزمون.</p>
                        <span class="tag">بانک سؤال معتبر</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" id="features" style="padding-top: 0;">
            <div class="tz-section-head">
                <div class="tz-eyebrow" style="background: var(--brand-soft); color: var(--brand-dark);">امکانات</div>
                <h2>هرچی برای تمرین جدی لازمه</h2>
                <p>نه یه لیست سؤال ساده — یه شبیه‌سازی واقعی از خود جلسه‌ی آزمون.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="tz-card tone-orange">
                        <div class="icon">📚</div>
                        <h3>بانک سؤال طبقه‌بندی‌شده</h3>
                        <p>سؤال‌ها بر اساس پایه، درس و موضوع دسته‌بندی شدن — دقیقاً مطابق ساختار واقعی آزمون تیزهوشان.</p>
                        <span class="tag">درس به درس</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-card tone-pink">
                        <div class="icon">⏱️</div>
                        <h3>آزمون زمان‌دار</h3>
                        <p>هر آزمون با تایمر واقعی برگزار می‌شه؛ همون فشار زمانی جلسه‌ی امتحان واقعی رو تجربه می‌کنی.</p>
                        <span class="tag">شبیه‌سازی واقعی</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tz-card tone-green">
                        <div class="icon">📊</div>
                        <h3>کارنامه و مرور پاسخ‌ها</h3>
                        <p>بلافاصله بعد از پایان آزمون، نمره و مرور سؤال‌به‌سؤال پاسخ‌ها رو می‌بینی — نه فقط یه عدد خام.</p>
                        <span class="tag">نتیجه‌ی فوری</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="tz-section" id="how">
            <div class="tz-section-head">
                <div class="tz-eyebrow" style="background: var(--brand-soft); color: var(--brand-dark);">نحوه‌ی شروع</div>
                <h2>سه قدم تا اولین آزمونت</h2>
            </div>
            <div class="tz-steps">
                <div class="tz-step">
                    <div class="badge-num">۱</div>
                    <div><h3>ثبت‌نام کن</h3><p>چند ثانیه کار داره — فقط نام، ایمیل و پایه‌ی تحصیلی.</p></div>
                </div>
                <div class="tz-step">
                    <div class="badge-num">۲</div>
                    <div><h3>آزمونت رو انتخاب کن</h3><p>بر اساس پایه‌ت، لیست آزمون‌های در دسترس رو توی داشبورد می‌بینی.</p></div>
                </div>
                <div class="tz-step">
                    <div class="badge-num">۳</div>
                    <div><h3>با تایمر واقعی امتحان بده</h3><p>بلافاصله بعد از پایان، کارنامه و مرور پاسخ‌ها آماده‌ست.</p></div>
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

        <section class="tz-section tz-faq" id="faq" style="padding-top: 0;">
            <div class="tz-section-head">
                <div class="tz-eyebrow" style="background: var(--brand-soft); color: var(--brand-dark);">سؤالات متداول</div>
                <h2>چیزهایی که معمولاً می‌پرسن</h2>
            </div>

            <div class="accordion" id="tzFaqAccordion" style="max-width: 42rem; margin: 0 auto;">
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

        <section class="tz-section" style="padding-top: 0;">
            <div class="tz-cta-banner">
                <h2>همین امروز شروع کن</h2>
                <p>ثبت‌نام رایگانه و چند ثانیه بیشتر طول نمی‌کشه.</p>
                <a href="{{ route('register') }}" class="btn-pill btn-white">ثبت‌نام رایگان</a>
            </div>
        </section>

    </div>

    <footer class="tz-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="tz-logo" style="margin-bottom: 0.75rem;">تیز<span>ینو</span></div>
                    <p style="color: #FFD9B3; font-size: 0.88rem; max-width: 20rem;">سامانه‌ی تمرین آنلاین آزمون تیزهوشان برای دانش‌آموز، والدین و دبیر.</p>
                </div>
                <div class="col-6 col-md-4">
                    <h4>لینک‌ها</h4>
                    <a href="{{ route('home') }}">صفحه‌ی اصلی</a>
                    <a href="#features">امکانات</a>
                    <a href="#faq">سؤالات متداول</a>
                </div>
                <div class="col-6 col-md-4">
                    <h4>حساب کاربری</h4>
                    <a href="{{ route('login') }}">ورود</a>
                    <a href="{{ route('register') }}">ثبت‌نام رایگان</a>
                </div>
            </div>

            <div class="tz-footer-bottom">
                <div>© {{ date('Y') }} تیزینو</div>
                <div>ساخته‌شده برای آزمون تیزهوشان</div>
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
                        feedback.style.color = 'var(--green)';
                    } else {
                        feedback.textContent = 'پاسخ درست «نوشتن» بود — رابطه‌ی «ابزار و کاربردش».';
                        feedback.style.color = 'var(--text-muted)';
                    }
                });
            });

            document.querySelectorAll('.tz-faq .accordion-collapse').forEach(function (el) {
                el.addEventListener('show.bs.collapse', function () {
                    el.closest('.accordion-item').classList.add('is-open');
                });
                el.addEventListener('hide.bs.collapse', function () {
                    el.closest('.accordion-item').classList.remove('is-open');
                });
            });
        })();
    </script>

</body>
</html>
