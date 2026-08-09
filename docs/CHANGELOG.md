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