<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamAttemptResource\Pages;
use App\Filament\Forms\Components\JalaliDateTimePicker;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Support\JalaliDate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class ExamAttemptResource extends Resource
{
    protected static ?string $model = ExamAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static UnitEnum|string|null $navigationGroup = 'سیستم آزمون';

    protected static ?string $navigationLabel = 'نتایج آزمون‌ها';

    protected static ?string $modelLabel = 'تلاش آزمون';

    protected static ?string $pluralModelLabel = 'نتایج آزمون‌ها';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('دانش‌آموز')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('exam.title')
                    ->label('آزمون')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('started_at')
                    ->label('شروع')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable(),

                TextColumn::make('submitted_at')
                    ->label('پایان')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->placeholder('—')
                    ->sortable(),

                IconColumn::make('is_finished')
                    ->label('وضعیت')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('score')
                    ->label('نمره')
                    ->sortable(),

                TextColumn::make('correct_answers')
                    ->label('درست')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('wrong_answers')
                    ->label('غلط')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('blank_answers')
                    ->label('بی‌پاسخ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('exam_id')
                    ->label('آزمون')
                    ->options(fn () => Exam::query()->pluck('title', 'id'))
                    ->searchable(),

                TernaryFilter::make('is_finished')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('پایان‌یافته')
                    ->falseLabel('در حال انجام'),

                Filter::make('started_at')
                    ->label('بازه‌ی شروع')
                    ->schema([
                        JalaliDateTimePicker::make('from')->label('از تاریخ')->withoutTime(),
                        JalaliDateTimePicker::make('until')->label('تا تاریخ')->withoutTime(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('started_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('started_at', '<=', $date)
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamAttempts::route('/'),
            'view' => Pages\ViewExamAttempt::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
