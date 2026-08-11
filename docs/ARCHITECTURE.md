# Project Architecture

## Current Modules

- بانک سوال
- سیستم آزمون

## Future Modules

- داشبورد دانش‌آموز
- آزمون آنلاین
- آزمون زمان‌دار
- کارنامه
- تحلیل آزمون
- نمودار پیشرفت
- مدیریت کاربران
- مدیریت نقش‌ها
- تنظیمات سایت

## Tech Stack

- Laravel 13.23.0
- Filament 5.7.4
- PHP ^8.3
- Livewire 4.3.3
- MySQL

## Admin Resources

- Grades
- Subjects
- Topics
- Questions
- QuestionOptions
- Exams
- ExamAttempts (نتایج آزمون‌ها — read-only، فقط View و Delete)

## Notes

- `config/app.php` مقدار `timezone` باید از `env('APP_TIMEZONE', 'UTC')` خونده بشه، نه هاردکد. پروژه روی `Asia/Tehran` تنظیم شده.
