<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    public static function defaults(): array
    {
        return [
            'color_brand' => '#F97316',
            'color_bg' => '#0A1224',
            'color_card' => '#111D35',

            'nav_links' => [
                ['label' => 'صفحه اصلی', 'url' => '/'],
                ['label' => 'بانک سوالات', 'url' => '/bank'],
                ['label' => 'آزمون‌ها', 'url' => '/dashboard'],
                ['label' => 'سوالات متداول', 'url' => '#faq'],
            ],
            'btn_login' => 'ورود',
            'btn_register' => 'ثبت‌نام',

            'hero_title' => 'تیزینو',
            'hero_subtitle' => 'آمادگی هوشمند برای موفقیت',
            'hero_text' => 'با تیزینو، مسیری مطمئن و اصولی برای آمادگی در آزمون‌های مهم و رقابتی پیش رو دارید. تمرین بیشتر، نتیجه بهتر!',
            'hero_btn_text' => 'همین حالا شروع کنید',

            'card_enabled' => true,
            'card_title' => 'آزمون جامع ریاضی',
            'card_progress_label' => 'پیشرفت شما در آزمون',
            'card_percent' => 75,
            'card_total' => '100',
            'card_correct' => '75',
            'card_wrong' => '25',
            'card_time' => '45:30',
            'card_btn_text' => 'مشاهده جزئیات آزمون',

            'stats' => [
                ['icon' => 'target', 'source' => 'manual', 'value' => '۹۸٪', 'label' => 'نرخ موفقیت'],
                ['icon' => 'list', 'source' => 'questions', 'value' => '', 'label' => 'سوالات استاندارد'],
                ['icon' => 'users', 'source' => 'manual', 'value' => '۱۲۰۰۰+', 'label' => 'دانش‌آموزان فعال'],
            ],

            'why_title' => 'چرا تیزینو؟',
            'features' => [
                ['icon' => 'clipboard', 'title' => 'آزمون‌های شبیه‌سازی شده', 'text' => 'تجربه واقعی از شرایط آزمون'],
                ['icon' => 'layers', 'title' => 'بانک سوالات گسترده', 'text' => 'پوشش کامل مباحث درسی'],
                ['icon' => 'chart', 'title' => 'تحلیل هوشمند عملکرد', 'text' => 'مشاهده نقاط قوت و ضعف'],
                ['icon' => 'phone', 'title' => 'دسترسی در همه جا', 'text' => 'روی تمام دستگاه‌ها'],
            ],

            'faq_enabled' => true,
            'faq_title' => 'سوالات متداول',
            'faqs' => [
                ['q' => 'آیا استفاده از تیزینو رایگانه؟', 'a' => 'بله. ثبت‌نام و تمرین توی تیزینو رایگانه.'],
                ['q' => 'برای کدوم پایه‌هاست؟', 'a' => 'در حال حاضر برای پایه‌ی ششم و نهم.'],
            ],

            'cta_enabled' => true,
            'cta_title' => 'همین امروز شروع کن',
            'cta_text' => 'ثبت‌نام رایگانه و چند ثانیه بیشتر طول نمی‌کشه.',
            'cta_btn_text' => 'ثبت‌نام رایگان',

            'footer_text' => 'سامانه‌ی تمرین آنلاین آزمون تیزهوشان.',
            'footer_copyright' => 'تیزینو',
        ];
    }

    /** تنظیمات ذخیره‌شده، ادغام‌شده روی پیش‌فرض‌ها. */
    public static function content(): array
    {
        $stored = [];
        try {
            $stored = static::query()->where('key', 'content')->value('value') ?? [];
        } catch (\Throwable $e) {
            // جدول هنوز migrate نشده
        }

        return array_merge(static::defaults(), $stored);
    }

    public static function put(array $data): void
    {
        static::query()->updateOrCreate(['key' => 'content'], ['value' => $data]);
    }
}
