<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Forms\Components\JalaliDateTimePicker;
use App\Models\Subscription;
use App\Support\JalaliDate;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static \UnitEnum|string|null $navigationGroup = 'اشتراک و پرداخت';

    protected static ?string $navigationLabel = 'اشتراک‌های کاربران';

    protected static ?string $modelLabel = 'اشتراک';

    protected static ?string $pluralModelLabel = 'اشتراک‌های کاربران';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات اشتراک')
                ->columns(2)
                ->components([
                    Select::make('user_id')
                        ->label('کاربر')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('subscription_plan_id')
                        ->label('پلن')
                        ->relationship('plan', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    JalaliDateTimePicker::make('starts_at')
                        ->label('تاریخ شروع')
                        ->required(),

                    JalaliDateTimePicker::make('ends_at')
                        ->label('تاریخ پایان')
                        ->required(),

                    Select::make('status')
                        ->label('وضعیت')
                        ->options([
                            'active' => 'فعال',
                            'expired' => 'منقضی‌شده',
                            'cancelled' => 'لغوشده',
                        ])
                        ->default('active')
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('plan.title')
                    ->label('پلن')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('شروع')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('پایان')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'فعال',
                        'expired' => 'منقضی‌شده',
                        'cancelled' => 'لغوشده',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'active' => 'فعال',
                        'expired' => 'منقضی‌شده',
                        'cancelled' => 'لغوشده',
                    ]),

                SelectFilter::make('subscription_plan_id')
                    ->label('پلن')
                    ->relationship('plan', 'title'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
