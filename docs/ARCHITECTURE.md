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
- **تقویم شمسی (پروژه‌محور، بدون پکیج خارجی):** چون Packagist از طریق ISP روی حداقل یکی از دو سیستم توسعه فیلتر می‌شه (TCP وصل می‌شه ولی TLS handshake گیر می‌کنه — فیلترینگ مبتنی بر SNI)، امکان `composer require` برای هیچ پکیج تقویم جلالی‌ای (نه `morilog/jalali`، نه `shahriyar3/filament-calendar`) وجود نداشت. به‌جاش:
  - `app/Support/JalaliDate.php`: تبدیل میلادی↔شمسی خودنوشته (الگوریتم ریاضی استاندارد تقویم جلالی)، بدون هیچ وابستگی. متدهای اصلی: `format()` (نمایش)، `toJalali()`/فرمول معکوس داخل `JalaliDateTimePicker` (ورودی)، `toPersianDigits()`، `monthNames()`.
  - نمایش تاریخ در جدول‌ها/infolist های Filament: به‌جای `->dateTime('Y/m/d H:i')` از `->formatStateUsing(fn ($state) => JalaliDate::format($state))` استفاده می‌شه.
  - ورودی تاریخ در فرم‌ها: `app/Filament/Forms/Components/JalaliDateTimePicker.php` (+ Blade view در `resources/views/filament/forms/components/jalali-date-time-picker.blade.php`) یک فیلد سفارشی Filament (extends `Field`) با تقویم پاپ‌آپ (گرید روزهای ماه، ناوبری بین ماه‌ها، دکمه‌ی امروز/پاک‌کردن/تأیید) که کاملاً با Alpine.js/جاوااسکریپت خام ساخته شده. مقدار ذخیره‌شده در دیتابیس همچنان میلادی (`Y-m-d H:i:s`) می‌مونه؛ تبدیل شمسی↔میلادی سمت کلاینت انجام می‌شه.
    - استفاده در: `ExamForm` (`start_at`/`end_at`)، `SubscriptionResource` (`starts_at`/`ends_at`)، فیلتر «بازه‌ی شروع» در `ExamAttemptResource` (با `->withoutTime()`).
    - `->withoutTime()` برای فیلدهای فقط-تاریخ (بدون ساعت).
  - **الگوی اعتبارسنجی تاریخ با فیلد سفارشی:** متد آماده‌ی Filament مثل `->after('field')` روی این فیلد سفارشی درست کار نمی‌کنه (به مقدار فیلد خواهر دسترسی درست پیدا نمی‌کنه). به‌جاش باید قانون رو صریح با closure نوشت:
    ```php
    ->rule(function (Get $get) {
        return function (string $attribute, $value, Closure $fail) use ($get) {
            $start = $get('start_at');
            if (blank($start) || blank($value)) return;
            if (! Carbon::parse($value)->gt(Carbon::parse($start))) {
                $fail('...');
            }
        };
    })
    ```
  - **اعتبارسنجی «تاریخ گذشته» فقط روی ایجاد:** چون `ExamForm` بین صفحه‌ی Create و Edit مشترکه، محدودیت «تاریخ نباید گذشته باشه» فقط وقتی `$livewire instanceof \Filament\Resources\Pages\CreateRecord` باشه فعال می‌شه؛ وگرنه ویرایش آزمون‌های قدیمی (که تاریخشون طبیعتاً گذشته‌ست) بلاک می‌شد.
  - **باگ کشویی‌های Jalali:** مقدار مدل Alpine برای روز/ماه/سال باید حتماً رشته‌ای (`string`) باشه، نه عددی (`.number` modifier)؛ چون placeholder خالی (`''`) باید دقیقاً با مقدار مدل مطابقت داشته باشه، وگرنه مرورگر به‌صورت پیش‌فرض یه گزینه‌ی دیگه (نه placeholder) رو نمایش می‌ده بدون این‌که واقعاً انتخاب شده باشه.
