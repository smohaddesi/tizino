## 2026-08-03

### Added

- Grade Resource
- Subject Resource
- Grade ↔ Subject relationship
- Persian admin labels

### Changed

- Migrated admin resources to Filament v5 structure

## 2026-08-0X (تاریخ واقعی رو خودت جایگزین کن)

### Added

- Topic Resource (Filament v5)
- Question Resource (Filament v5)
- QuestionOption Resource (Filament v5)
## 2026-08-08

### Added

- Exam Resource (Filament v5)

## 2026-08-08

### Added

- Exam Resource (Filament v5)
- QuestionsRelationManager for Exam (مدیریت سؤال‌های هر آزمون)
## 2026-08-08

### Added

- Exam Resource (Filament v5)
- QuestionsRelationManager for Exam
- User roles (spatie/laravel-permission v8.3.0) — admin/student
- Panel access restricted to admin role only

## 2026-08-08 (ادامه)

### Added

- Student registration & login (outside Filament panel)
- Auto-assign "student" role on registration
- Placeholder student dashboard

## 2026-08-09

### Added

- فیلد `grade_id` روی جدول `users` (تعیین پایه‌ی دانش‌آموز)
- سیستم کامل گرفتن آزمون (شروع، پاسخ‌دهی AJAX، تایمر سمت سرور، پایان خودکار/دستی، نتیجه و مرور سؤالات)
- نمایش لیست آزمون‌های در دسترس در داشبورد دانش‌آموز (بر اساس پایه، فعال بودن، بازه‌ی زمانی)

### Changed

- لیبل‌های فارسی برای فرم‌ها و جدول‌های Topic، Question، QuestionOption
- بازطراحی فرم Question: گزینه‌های سؤال حالا از طریق Repeater داخل همون فرم مدیریت می‌شن (نه Resource جدا)
- QuestionOptionResource از منوی ناوبری مخفی شد (چون از داخل فرم Question مدیریت می‌شه)
- انتخاب موضوع در فرم سؤال به‌صورت Select با نمایش «درس » موضوع»
## 2026-08-09 (ادامه)

### Changed

- بازطراحی ساختار درس‌ها (Subject) بر اساس ساختار واقعی آزمون تیزهوشان:
  - پایه‌ی ششم: فقط ۴ درس هوش/استعداد تحلیلی (حذف درس‌های کتاب‌محور که در آزمون واقعی نیستند)
  - پایه‌ی نهم: ترکیب درس‌های تحصیلی + همون ۴ درس هوش
- اضافه شدن `TopicSeeder` با موضوعات واقعی زیر هر درس

## 2026-08-11

### Added

- `ExamAttemptResource` (Filament v5) — نمایش و مرور نتایج آزمون‌های دانش‌آموزان برای ادمین
  - صفحه‌ی List: دانش‌آموز، آزمون، شروع/پایان، وضعیت، نمره، تعداد پاسخ درست/غلط/بی‌پاسخ
  - فیلترها: آزمون (Select)، وضعیت (پایان‌یافته/در حال انجام)، بازه‌ی تاریخ شروع
  - صفحه‌ی View: اطلاعات کلی + مرور سؤال‌به‌سؤال پاسخ‌ها (پاسخ دانش‌آموز، پاسخ صحیح، نتیجه)
  - Read-only: بدون صفحه‌ی Create/Edit، فقط View و Delete

### Fixed

- باگ Timezone: `config/app.php` مقدار `timezone` رو به‌صورت هاردکد `'UTC'` داشت و از `.env` نمی‌خوند. همین باعث می‌شد `now()` همیشه بر اساس UTC محاسبه بشه و آزمون‌ها بر اساس بازه‌ی زمانی (start_at/end_at) اشتباه فیلتر بشن (مثلاً آزمون فعال به‌عنوان «هنوز شروع نشده» نمایش داده می‌شد).
  - اصلاح به `'timezone' => env('APP_TIMEZONE', 'UTC')`
  - `.env` با `APP_TIMEZONE=Asia/Tehran` تنظیم شد
  - کش قدیمی `bootstrap/cache/config.php` که مانع اعمال شدن تنظیمات جدید می‌شد، پاک شد

## 2026-08-12

### Added

- `UserResource` (Filament v5) — مدیریت کامل کاربران (دانش‌آموز و ادمین) برای ادمین
  - فرم: نام، ایمیل، نقش (ادمین/دانش‌آموز)، پایه (فقط برای دانش‌آموز)، رمز عبور
  - جدول: نام، ایمیل، نقش (badge)، پایه، تاریخ ثبت‌نام
  - فیلترها: نقش، پایه
  - اکشن‌ها: ویرایش، ریست رمز عبور (مودال جدا)، حذف
  - محافظت در برابر حذف حساب ادمین لاگین‌شده توسط خودش
  - همگام‌سازی نقش با `spatie/laravel-permission` از طریق هوک‌های `CreateUser`/`EditUser` (چون `role` ستون مستقیم روی جدول `users` نیست)

### Fixed

- اصلاح namespace اشتباه `Grade::title` (نه `name`) در فرم/جدول/فیلتر `UserResource`
- اصلاح namespace کلاس `Get` در Filament 5.7.4: از `Filament\Schemas\Components\Utilities\Get` باید import بشه، نه `Filament\Forms\Get`

### Notes

- یادآوری ساختاری: هر Resource باید مستقیم زیر `app/Filament/Resources/` باشه (نه زیر `app/Filament/` یا داخل زیرپوشه‌ی خودش) تا PSR-4 autoload درست کار کنه.
