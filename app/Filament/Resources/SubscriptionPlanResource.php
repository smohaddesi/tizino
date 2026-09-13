<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use App\Support\JalaliDate;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static \UnitEnum|string|null $navigationGroup = 'اشتراک و پرداخت';

    protected static ?string $navigationLabel = 'پلن‌های اشتراک';

    protected static ?string $modelLabel = 'پلن اشتراک';

    protected static ?string $pluralModelLabel = 'پلن‌های اشتراک';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات پلن')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('عنوان پلن')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('شناسه (Slug)')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('مثال: monthly یا yearly - فقط حروف انگلیسی، بدون فاصله'),

                    TextInput::make('duration_days')
                        ->label('مدت اعتبار (روز)')
                        ->required()
                        ->numeric()
                        ->minValue(1),

                    TextInput::make('price')
                        ->label('قیمت (تومان)')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->suffix('تومان'),

                    TextInput::make('sort_order')
                        ->label('ترتیب نمایش')
                        ->required()
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('فعال')
                        ->default(true),
                ]),

            Section::make('توضیحات')
                ->components([
                    Textarea::make('description')
                        ->label('توضیحات پلن')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('شناسه')
                    ->searchable(),

                TextColumn::make('duration_days')
                    ->label('مدت (روز)')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('قیمت')
                    ->money('IRT', divideBy: 1)
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
