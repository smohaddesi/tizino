# Project Architecture

## Current Modules

- بانک سوال
- سیستم آزمون
- صفحه‌ی فرود (Landing Page)

## Future Modules

- داشبورد دانش‌آموز
- آزمون آنلاین
- آزمون زمان‌دار
- کارنامه
- تحلیل آزمون
- نمودار پیشرفت
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
- `LandingController` (در `app/Http/Controllers/`) صفحه‌ی فرود عمومی رو با آمار زنده از دیتابیس رندر می‌کنه؛ روت `/` دیگه به `/admin` ریدایرکت نمی‌شه.
- الگوی «فیلد فیلتر مجازی» در فرم‌های Filament: فیلدهایی مثل `grade_filter`/`subject_filter` در `QuestionForm` که `dehydrated(false)` دارن و مستقیم ذخیره نمی‌شن، فقط برای فیلتر کردن گزینه‌های یک Select دیگه (مثل `topic_id`) به‌صورت آبشاری استفاده می‌شن. برای پیش‌پرکردنشون در حالت ویرایش، از `afterStateHydrated` با دسترسی به `$record` استفاده می‌شه.
- Import CSV (`ListQuestions.php`) به‌صورت دومرحله‌ای (بررسی بدون ذخیره → ثبت نهایی) طراحی شده، با یه متد مشترک `processCsv(..., bool $commit)` که هم برای پیش‌نمایش هم برای ثبت واقعی استفاده می‌شه.
- ابزار بک‌آپ (`backup-db.bat`/`restore-db.bat`) مسیر نصب MySQL رو خودکار زیر `D:\laragodev\bin\mysql\` یا `C:\laragon\bin\mysql\` پیدا می‌کنه.
