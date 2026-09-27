# Roadmap

## Phase 1 — Database

- [x] Grade
- [x] Subject
- [x] Topic
- [x] Question
- [x] QuestionOption
- [x] Exam Database
- [x] Exam Models

## Phase 2 — Filament Admin

- [x] Grade Resource
- [x] Subject Resource
- [x] Topic Resource
- [x] Question Resource
- [x] QuestionOption Resource
- [x] Exam Resource
- [x] ExamAttempt Resource (نمایش/مرور نتایج آزمون‌ها — read-only)

## Phase 3 — Student & Exam UI
- [x] Student registration & login
- [x] Exam taking flow (timer, answers, scoring, review)
- [x] Student Panel (Layout مشترک + سایدبار، داشبورد با کارت‌های آماری، پروفایل)
- [x] Question Bank student access (`/bank` با قفل اشتراک و پیش‌نمایش سؤال نمونه)
- [ ] Online Exam
- [ ] Timed Exam
- [ ] Reports / Analytics / Progress Charts (کارنامه، تحلیل آزمون، نمودار پیشرفت)
- [x] User Management (UserResource — مدیریت دانش‌آموزان و ادمین‌ها)
- [ ] Role Management
- [ ] Settings
- [x] Public Landing Page (نیاز به بهسازی بیشتر دارد)
- [x] بازطراحی بصری پنل ادمین (theme.css سفارشی + ویجت کاشی‌های داشبورد `DashboardTilesWidget`)

## Phase 4 — Localization

- [x] تقویم شمسی در سراسر سایت (نمایش تاریخ در پنل ادمین + صفحات دانش‌آموزی، و ورودی تاریخ در فرم‌های آزمون/اشتراک/فیلترها)
- [x] ارقام فارسی در سراسر پنل دانش‌آموز (اعداد، صفحه‌بندی، تایمر آزمون)
- [x] ارقام فارسی در پنل ادمین — ستون‌های عددی + View مشترک pagination (باقی‌مانده: تکرار الگو روی بقیه‌ی Resourceها)
