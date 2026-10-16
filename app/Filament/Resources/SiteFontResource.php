<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteFontResource\Pages\ManageSiteFonts;
use App\Models\SiteFont;
use App\Support\JalaliDate;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SiteFontResource extends Resource
{
    protected static ?string $model = SiteFont::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|UnitEnum|null $navigationGroup = 'تنظیمات';

    protected static ?string $navigationLabel = 'فونت‌های سایت';

    protected static ?string $modelLabel = 'فونت';

    protected static ?string $pluralModelLabel = 'فونت‌ها';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('family')
                ->label('نام فونت')
                ->helperText('مثلاً «یکان بوم». هر فونت یک بار ثبت می‌شود و وزن‌هایش در پایین اضافه می‌شوند.')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100),
            Repeater::make('variants')
                ->label('وزن‌ها')
                ->helperText('برای هر وزن یک فایل کافی است. اگر فونت فقط یک وزن دارد، همان را بگذارید.')
                ->schema([
                    Select::make('weight')
                        ->label('ضخامت')
                        ->options([
                            300 => 'نازک (۳۰۰)',
                            400 => 'معمولی (۴۰۰)',
                            500 => 'متوسط (۵۰۰)',
                            700 => 'ضخیم (۷۰۰)',
                            800 => 'خیلی ضخیم (۸۰۰)',
                        ])
                        ->default(400)
                        ->distinct()
                        ->required(),
                    FileUpload::make('file_path')
                        ->label('فایل فونت')
                        ->helperText('یکی از فرمت‌های woff2 ،woff ،ttf ،otf (woff2 پیشنهاد می‌شود)')
                        ->disk('site_fonts')
                        ->preserveFilenames()
                        ->maxSize(5120)
                        ->required()
                        ->rules([
                            fn () => function (string $attribute, $value, \Closure $fail) {
                                $name = is_object($value) && method_exists($value, 'getClientOriginalName')
                                    ? $value->getClientOriginalName()
                                    : (string) $value;
                                if (! array_key_exists(strtolower(pathinfo($name, PATHINFO_EXTENSION)), SiteFont::FORMATS)) {
                                    $fail('فقط فایل woff2 ،woff ،ttf یا otf مجاز است.');
                                }
                            },
                        ]),
                ])
                ->columns(2)
                ->minItems(1)
                ->defaultItems(1)
                ->addActionLabel('افزودن وزن دیگر')
                ->itemLabel(fn (array $state): ?string => isset($state['weight'])
                    ? 'وزن '.JalaliDate::toPersianDigits((string) $state['weight'])
                    : null)
                ->columnSpanFull(),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family')->label('نام فونت')->searchable()->sortable(),
                TextColumn::make('variants')->label('وزن‌ها')
                    ->getStateUsing(fn (SiteFont $record) => JalaliDate::toPersianDigits(
                        collect($record->variants ?? [])->pluck('weight')->sort()->implode('، ')
                    )),
                IconColumn::make('is_active')->label('فعال')->boolean(),
                TextColumn::make('created_at')->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => $state ? JalaliDate::toPersianDigits(
                        method_exists(JalaliDate::class, 'format') ? JalaliDate::format($state) : $state->format('Y/m/d')
                    ) : '—'),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('فعال‌سازی')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (SiteFont $record) => ! $record->is_active)
                    ->action(function (SiteFont $record) {
                        $record->activate();
                        Notification::make()->title('فونت «'.$record->family.'» فعال شد')->success()->send();
                    }),
                Action::make('deactivate')
                    ->label('بازگشت به فونت پیش‌فرض')
                    ->color('gray')
                    ->visible(fn (SiteFont $record) => $record->is_active)
                    ->action(function (SiteFont $record) {
                        $record->update(['is_active' => false]);
                        Notification::make()->title('فونت پیش‌فرض برگشت')->success()->send();
                    }),
                EditAction::make()->label('ویرایش'),
                DeleteAction::make()->label('حذف'),
            ])
            ->toolbarActions([DeleteBulkAction::make()->label('حذف انتخاب‌شده‌ها')])
            ->emptyStateHeading('هنوز فونتی اضافه نشده');
    }

    public static function getPages(): array
    {
        return ['index' => ManageSiteFonts::route('/')];
    }
}
