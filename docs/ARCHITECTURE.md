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
- Users (مدیریت دانش‌آموزان و ادمین‌ها، شامل نقش و ریست رمز عبور)

## Notes

- `config/app.php` مقدار `timezone` باید از `env('APP_TIMEZONE', 'UTC')` خونده بشه، نه هاردکد. پروژه روی `Asia/Tehran` تنظیم شده.
- هر Resource باید مستقیم زیر `app/Filament/Resources/` باشه (نه زیر `app/Filament/`)، وگرنه PSR-4 autoload اون رو پیدا نمی‌کنه.
- در Filament 5.7.4: کلاس `Get` از `Filament\Schemas\Components\Utilities\Get` میاد (نه `Filament\Forms\Get`)، و `Section` layout از `Filament\Schemas\Components\Section` میاد (نه `Filament\Infolists\Components\Section`).
- ستون نام `Grade` در دیتابیس `title` است، نه `name`.
