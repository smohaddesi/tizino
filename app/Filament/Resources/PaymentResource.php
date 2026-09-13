<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Support\JalaliDate;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static \UnitEnum|string|null $navigationGroup = 'اشتراک و پرداخت';

    protected static ?string $navigationLabel = 'تراکنش‌های پرداخت';

    protected static ?string $modelLabel = 'تراکنش';

    protected static ?string $pluralModelLabel = 'تراکنش‌های پرداخت';

    protected static ?int $navigationSort = 3;

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('اطلاعات تراکنش')
                ->columns(2)
                ->components([
                    TextEntry::make('user.name')
                        ->label('کاربر'),

                    TextEntry::make('plan.title')
                        ->label('پلن'),

                    TextEntry::make('amount')
                        ->label('مبلغ')
                        ->money('IRT', divideBy: 1),

                    TextEntry::make('gateway')
                        ->label('درگاه'),

                    TextEntry::make('authority')
                        ->label('Authority')
                        ->copyable(),

                    TextEntry::make('ref_id')
                        ->label('شماره‌ی پیگیری (RefID)')
                        ->copyable()
                        ->placeholder('—'),

                    TextEntry::make('status')
                        ->label('وضعیت')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => match ($state) {
                            'pending' => 'در انتظار',
                            'success' => 'موفق',
                            'failed' => 'ناموفق',
                            default => $state,
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'success' => 'success',
                            'failed' => 'danger',
                            default => 'gray',
                        }),

                    TextEntry::make('paid_at')
                        ->label('تاریخ پرداخت')
                        ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                        ->placeholder('—'),

                    TextEntry::make('created_at')
                        ->label('تاریخ ثبت')
                        ->formatStateUsing(fn ($state) => JalaliDate::format($state)),
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

                TextColumn::make('amount')
                    ->label('مبلغ')
                    ->money('IRT', divideBy: 1)
                    ->sortable(),

                TextColumn::make('gateway')
                    ->label('درگاه'),

                TextColumn::make('ref_id')
                    ->label('کد پیگیری')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'در انتظار',
                        'success' => 'موفق',
                        'failed' => 'ناموفق',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('paid_at')
                    ->label('تاریخ پرداخت')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        'pending' => 'در انتظار',
                        'success' => 'موفق',
                        'failed' => 'ناموفق',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
