<?php

namespace App\Filament\Pages;

use App\Models\LandingSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use UnitEnum;

class LandingPageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|UnitEnum|null $navigationGroup = 'تنظیمات';

    protected static ?string $navigationLabel = 'صفحه اصلی سایت';

    protected static ?string $title = 'ویرایش صفحه اصلی سایت';

    protected static ?string $slug = 'landing-page';

    protected string $view = 'filament.pages.landing-page-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(LandingSetting::content());
    }

    public function form(Schema $schema): Schema
    {
        $icons = [
            'target' => 'هدف', 'list' => 'لیست', 'users' => 'کاربران', 'clipboard' => 'کلیپ‌بورد',
            'layers' => 'لایه‌ها', 'chart' => 'نمودار', 'phone' => 'موبایل', 'star' => 'ستاره',
            'check' => 'تیک', 'clock' => 'ساعت',
        ];

        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('landing')->columnSpanFull()->tabs([
                    Tab::make('ظاهر و منو')->schema([
                        Section::make('رنگ‌ها')->columns(3)->schema([
                            ColorPicker::make('color_brand')->label('رنگ اصلی (دکمه‌ها)'),
                            ColorPicker::make('color_bg')->label('رنگ پس‌زمینه'),
                            ColorPicker::make('color_card')->label('رنگ کارت‌ها'),
                        ]),
                        Section::make('منو و دکمه‌های بالای صفحه')->schema([
                            TextInput::make('btn_login')->label('متن دکمه ورود'),
                            TextInput::make('btn_register')->label('متن دکمه ثبت‌نام'),
                            Repeater::make('nav_links')->label('لینک‌های منو')->columns(2)->schema([
                                TextInput::make('label')->label('عنوان')->required(),
                                TextInput::make('url')->label('آدرس')->required(),
                            ])->defaultItems(0),
                        ]),
                    ]),
                    Tab::make('بخش اصلی (Hero)')->schema([
                        TextInput::make('hero_title')->label('عنوان بزرگ'),
                        TextInput::make('hero_subtitle')->label('زیرعنوان رنگی'),
                        Textarea::make('hero_text')->label('توضیح')->rows(3),
                        TextInput::make('hero_btn_text')->label('متن دکمه اصلی'),
                    ]),
                    Tab::make('کارت آزمون نمونه')->columns(2)->schema([
                        Toggle::make('card_enabled')->label('نمایش کارت')->columnSpanFull(),
                        TextInput::make('card_title')->label('عنوان آزمون'),
                        TextInput::make('card_progress_label')->label('برچسب پیشرفت'),
                        TextInput::make('card_percent')->label('درصد پیشرفت')->numeric()->minValue(0)->maxValue(100),
                        TextInput::make('card_total')->label('تعداد سوالات'),
                        TextInput::make('card_correct')->label('پاسخ صحیح'),
                        TextInput::make('card_wrong')->label('پاسخ غلط'),
                        TextInput::make('card_time')->label('زمان'),
                        TextInput::make('card_btn_text')->label('متن دکمه'),
                    ]),
                    Tab::make('آمار')->schema([
                        Repeater::make('stats')->label('کارت‌های آمار')->columns(4)->schema([
                            Select::make('icon')->label('آیکون')->options($icons)->required(),
                            Select::make('source')->label('منبع عدد')->options([
                                'manual' => 'دستی',
                                'questions' => 'تعداد سوالات فعال (پایگاه داده)',
                                'exams' => 'تعداد آزمون‌های فعال (پایگاه داده)',
                                'grades' => 'تعداد پایه‌ها (پایگاه داده)',
                                'users' => 'تعداد کاربران (پایگاه داده)',
                            ])->default('manual')->live()->required(),
                            TextInput::make('value')->label('مقدار (برای حالت دستی)'),
                            TextInput::make('label')->label('عنوان')->required(),
                        ])->defaultItems(0),
                    ]),
                    Tab::make('چرا تیزینو؟')->schema([
                        TextInput::make('why_title')->label('عنوان بخش'),
                        Repeater::make('features')->label('ویژگی‌ها')->columns(3)->schema([
                            Select::make('icon')->label('آیکون')->options($icons)->required(),
                            TextInput::make('title')->label('عنوان')->required(),
                            TextInput::make('text')->label('توضیح کوتاه'),
                        ])->defaultItems(0),
                    ]),
                    Tab::make('سوالات متداول')->schema([
                        Toggle::make('faq_enabled')->label('نمایش بخش'),
                        TextInput::make('faq_title')->label('عنوان بخش'),
                        Repeater::make('faqs')->label('سوال‌ها')->schema([
                            TextInput::make('q')->label('سوال')->required(),
                            Textarea::make('a')->label('پاسخ')->rows(2)->required(),
                        ])->defaultItems(0),
                    ]),
                    Tab::make('دعوت به ثبت‌نام و فوتر')->schema([
                        Toggle::make('cta_enabled')->label('نمایش بخش دعوت به ثبت‌نام'),
                        TextInput::make('cta_title')->label('عنوان'),
                        Textarea::make('cta_text')->label('متن')->rows(2),
                        TextInput::make('cta_btn_text')->label('متن دکمه'),
                        TextInput::make('footer_text')->label('متن فوتر'),
                        TextInput::make('footer_copyright')->label('نام در کپی‌رایت'),
                    ]),
                ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('ذخیره تغییرات')->submit('save'),
            Action::make('reset')->label('بازگشت به پیش‌فرض')->color('gray')->requiresConfirmation()
                ->action(function () {
                    LandingSetting::put(LandingSetting::defaults());
                    $this->form->fill(LandingSetting::defaults());
                    Notification::make()->title('به حالت پیش‌فرض برگشت')->success()->send();
                }),
            Action::make('view')->label('مشاهده سایت')->color('gray')->url(url('/'), shouldOpenInNewTab: true),
        ];
    }

    public function save(): void
    {
        LandingSetting::put($this->form->getState());

        Notification::make()->title('ذخیره شد')->success()->send();
    }
}
