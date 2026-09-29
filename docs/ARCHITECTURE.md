# Project Architecture

## Current Modules

- بانک سوال
- سیستم آزمون
- صفحه‌ی فرود (Landing Page)
- پنل دانش‌آموز (داشبورد، بانک سؤال با قفل اشتراک، اشتراک، پروفایل)

## Future Modules

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
- **فرم ورود سؤال (`QuestionForm`) — ساختار ساده‌شده:** فرم تک‌ستونه‌ست (`->columns(1)`) و همه‌ی بلوک‌ها (Section و Repeater) تمام‌عرض‌ان. ترتیب از بالا: موضوع + سطح سختی ← متن سؤال ← تصویر سؤال ← هشدار سؤال تکراری ← گزینه‌ها (Repeater) ← پاسخ تشریحی (متن + تصویر) ← «تنظیمات بیشتر» (جمع‌شونده: زمان پاسخ، منبع، فعال، نمونه‌ی رایگان) ← «پیش‌نمایش» (جمع‌شونده).
  - **الگوی «فیلد فیلتر مجازی» (`grade_filter`/`subject_filter`) از `QuestionForm` حذف شد.** به‌جاش `topic_id` یک Select جستجوپذیر با گروه‌بندی «پایه › درس» است (`groupedTopicOptions()`). `CreateQuestion` هم دیگه چیزی درباره‌ی `grade_filter`/`subject_filter` پر نمی‌کنه، فقط `topic_id`، `difficulty` و `answer_time` آخرین سؤال ثبت‌شده (از session).
  - `difficulty` با `ToggleButtons` (۱ تا ۵، ارقام فارسی) انتخاب می‌شه، نه ورودی عددی.
  - **`answer_time` اختیاری است** (nullable در دیتابیس، بدون مقدار پیش‌فرض در فرم). برای سؤال‌های صرفاً بانک‌سؤالی لازم نیست پر بشه؛ زمان آزمون به‌صورت کلی روی خود آزمون تعریف می‌شه. Import CSV هم ستون خالی رو `null` ذخیره می‌کنه.
  - **پاسخ تشریحی سؤال:** بخش مستقل (بعد از گزینه‌ها) با فیلد متن `answer_explanation` و تصویر `answer_explanation_image` (آپلود `FileUpload`، دیسک پیش‌فرض Filament).
  - **گزینه‌ها:** هر ردیف = متن + تصویر (اختیاری، فشرده با `panelLayout('compact')`) + سوییچ «صحیح». با روشن‌کردن یک سوییچ، بقیه خودکار خاموش می‌شن (`afterStateUpdated` با `$get('../../options')`/`$set`)؛ اعتبارسنجی سمت سرور «دقیقاً یک گزینه‌ی صحیح» همچنان پشتیبانه. `live` روی متن گزینه‌ها عمداً برداشته شده (کاهش رفت‌وبرگشت به سرور)، پس پیش‌نمایش با رفت‌وبرگشت‌های بعدی (بلور متن سؤال یا تغییر سوییچ صحیح) به‌روز می‌شه.
  - چک سؤال تکراری سبک شده: فقط ۳۰۰ سؤال آخر همون موضوع، و جفت‌هایی که طولشون اونقدر متفاوته که شباهت ۸۰٪ ممکن نیست، بدون `similar_text` رد می‌شن.
  - «تنظیمات بیشتر» فقط وقتی در ویرایش باز می‌شه که `source` پر باشه.
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
- **پنل دانش‌آموز — Layout مشترک (`resources/views/layouts/app.blade.php`):** سایدبار ثابت سمت راست (دسکتاپ) با پس‌زمینه‌ی سبزآبی تیره، آواتار کاربر (عکس آپلودی یا حرف اول اسم)، آیکون‌های Bootstrap Icons از CDN (`cdn.jsdelivr.net/npm/bootstrap-icons`). روی موبایل با دکمه‌ی ☰ و جاوااسکریپت خام (بدون وابستگی به Bootstrap JS Bundle) باز/بسته می‌شه. `show.blade.php` (صفحه‌ی حین آزمون) و `layouts/guest.blade.php` عمداً بیرون از این Layout موندن — اولی برای جلوگیری از خروج تصادفی حین آزمون، دومی چون قبل از لاگینه.
- **بانک سؤال دانش‌آموزی (`QuestionBankController`، مسیرهای `bank.*`):** مسیر ناوبری پایه→درس→موضوع→سؤال (`Subject::questions()` یک رابطه‌ی `hasManyThrough` جدید برای شمارش). صفحه‌ی موضوع صفحه‌بندی ۵تایی + فیلتر سطح سختی + جستجوی متنی داره. گزینه‌ها همیشه زیر سؤال نمایش داده می‌شن؛ دکمه‌ی «نمایش پاسخ» فقط تیک گزینه‌ی صحیح + توضیح تشریحی رو toggle می‌کنه (نه خود گزینه‌ها).
  - **قفل اشتراک بدون Middleware:** چون تصمیم گرفته شد به‌جای مسدودسازی کامل مسیر، محتوا به‌صورت جزئی نمایش داده بشه (چند سؤال نمونه‌ی رایگان باز، بقیه قفل)، چک اشتراک مستقیم داخل `QuestionBankController` با `$user->hasActiveSubscription()` انجام می‌شه، نه middleware. فیلد `questions.is_free_sample` (Toggle در پنل ادمین، کنار `is_active`) مشخص می‌کنه کدوم سؤالا نمونه‌ی رایگانن. برای سؤال قفل، `options` اصلاً به View فرستاده نمی‌شه (`setRelation('options', collect())`) — یعنی حتی با View Source هم گزینه‌ها دیده نمی‌شن، فقط خلاصه‌ی متن سؤال (بلورشده) به‌عنوان طعمه.
- **صفحه‌بندی فارسی:** چون View پیش‌فرض `pagination::bootstrap-5` لاراول ارقام انگلیسی می‌ده، یه View سفارشی توی `resources/views/pagination/bootstrap-5-fa.blade.php` ساخته شد که شماره‌ی صفحات رو با `JalaliDate::toPersianDigits()` رندر می‌کنه؛ با `->links('pagination.bootstrap-5-fa')` صدا زده می‌شه.
- **قانون ارقام فارسی در پنل دانش‌آموز:** هر عددی که توی پنل دانش‌آموز نمایش داده می‌شه (نمره، تعداد سؤال، سطح سختی، قیمت، تایمر آزمون و ...) باید از `JalaliDate::toPersianDigits()` رد بشه. تایمر صفحه‌ی حین آزمون چون سمت کلاینت با جاوااسکریپت ساخته می‌شه، یه تابع js جدا (`toFaDigits`) برای همین‌کار داره.
- **پروفایل دانش‌آموز (`ProfileController`, مسیر `/profile`):** ویرایش نام/ایمیل/پایه‌ی تحصیلی/رمز عبور (اختیاری، با چک رمز فعلی)/عکس پروفایل. ستون `users.avatar` (nullable string) روی دیسک `public` ذخیره می‌شه — نیازمند `php artisan storage:link`. مدل `User` از PHP Attributes (`#[Fillable(...)]`, `#[Hidden(...)]`) به‌جای پراپرتی‌های سنتی `$fillable`/`$hidden` استفاده می‌کنه؛ فیلد جدید باید به همون Attribute اضافه بشه.
- **داشبورد با کارت‌های آماری:** ۴ کارت رنگی (روز اعتبار اشتراک، آزمون در دسترس، آزمون انجام‌شده، تعداد سؤال بانک) که همه از داده‌ی واقعی `DashboardController` میان (نه هاردکد).
- **بازطراحی بصری پنل ادمین (`resources/css/filament/admin/theme.css`):** رنگ‌بندی/فاصله‌گذاری/فرم کامپوننت‌های Filament v5 (سایدبار، تب‌ها، جدول‌ها، کارت‌های آماری) با CSS دستی override شده، بدون تغییر زبان/جهت RTL/فونت Vazirmatn. چون نصب تم‌های آماده (`filafly/brisk` فقط Filament v4 رو ساپورت می‌کنه) با v5.7.4 ناسازگار بود، مسیر انتخاب‌شده CSS دستی + ویجت داشبورد سفارشیه، نه یه پکیج تم.
- **`DashboardTilesWidget` (`app/Filament/Widgets/DashboardTilesWidget.php` + View در `resources/views/filament/widgets/dashboard-tiles-widget.blade.php`):** جایگزین ویجت‌های پیش‌فرض `AccountWidget`/`FilamentInfoWidget` در داشبورد ادمین شد (این دو از `->widgets([...])` در `AdminPanelProvider.php` حذف شدن، ولی `discoverWidgets` دست‌نخورده مونده و ویجت جدید رو خودکار پیدا می‌کنه). کاشی‌های تخت‌رنگ به‌تفکیک گروه (سیستم آزمون، بانک سؤال، اشتراک و پرداخت، مدیریت کاربران)، هرکدوم با شمارش زنده از مدل مربوطه و لینک مستقیم به Resource. نکات فنی:
  - گرید هر گروه با `grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))` ساخته می‌شه (نه تعداد ستون ثابت) تا واقعاً ریسپانسیو باشه و کل عرض موجود رو پر کنه.
  - چون `columnSpan = 'full'` به‌تنهایی گرید داشبورد رو کامل پر نمی‌کرد، روی ریشه‌ی View مستقیم `style="grid-column: 1 / -1;"` گذاشته شده تا مستقل از رفتار گرید Filament همیشه کل عرض ردیف داشبورد رو بگیره.
  - کلاس‌های رنگی Tailwind (`bg-amber-500` و…) باید به‌صورت literal رشته‌ای کامل توی آرایه‌ی PHP نوشته بشن، نه ساخته‌شده با concatenation؛ چون Tailwind (از طریق `@source` در `theme.css`) روی متن خام فایل‌های زیر `app/Filament/**/*` regex می‌زنه، نه روی مقدار runtime.
- **بومی‌سازی ارقام فارسی، پنل ادمین (نه فقط دانش‌آموز):** الگوی `JalaliDate::toPersianDigits()` که قبلاً فقط روی پنل دانش‌آموز اعمال شده بود، حالا به ستون‌های عددی جدول‌های ادمین هم اضافه شده (`ExamsTable`, `SubjectsTable`, `SubscriptionPlanResource` و مشابه؛ باید روی بقیه‌ی Resourceها هم تکرار بشه). صفحه‌بندی (Pagination) خود Filament یه View مشترکه، نه بخشی از هر Resource؛ برای فارسی‌سازیش View‌های `resources/views/vendor/filament/components/pagination/index.blade.php` و `item.blade.php` باید publish (با `php artisan vendor:publish --tag=filament-views`) و ادیت بشن — این یه‌بار اصلاح، «نمایش X تا Y از Z»، دراپ‌داون تعداد در صفحه، و شماره‌صفحه‌ها رو در کل پنل ادمین پوشش می‌ده.
- **بانک سؤال دانش‌آموزی — انتخاب پاسخ (`resources/views/bank/topic.blade.php`):** کنار هر گزینه یه radio هست تا دانش‌آموز پاسخ خودش رو انتخاب کنه. با «نمایش پاسخ»: گزینه‌ی صحیح سبز + ✔، گزینه‌ی انتخابیِ غلط قرمز + ✘، پیام «درست/نادرست بود»، و باز شدن پاسخ تشریحی (متن + تصویر `answer_explanation_image` از دیسک پیش‌فرض Filament). بعد از نمایش پاسخ radioها قفل می‌شن؛ «پنهان کردن پاسخ» همه‌چیز رو ریست می‌کنه. کاملاً سمت کلاینت (جاوااسکریپت خام) است؛ **انتخاب‌های دانش‌آموز جایی ذخیره نمی‌شه**. سؤال‌های قفل‌شده هنوز هیچ‌کدوم از این داده‌ها (گزینه‌ها، پاسخ تشریحی، تصویرش) رو نمی‌گیرن.
- **محدودیت شناخته‌شده:** تصویر خود سؤال (`questions.image`) و تصویر گزینه‌ها (`question_options.image`) هنوز در `bank/topic.blade.php` نمایش داده نمی‌شن.
