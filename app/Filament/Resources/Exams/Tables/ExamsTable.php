<?php

namespace App\Filament\Resources\Exams\Tables;

use App\Support\JalaliDate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('title')
                    ->label('عنوان آزمون')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grade.title')
                    ->label('پایه')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('duration_minutes')
                    ->label('مدت (دقیقه)')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('total_questions')
                    ->label('تعداد سؤال')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('total_score')
                    ->label('نمره کل')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('start_at')
                    ->label('شروع')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('end_at')
                    ->label('پایان')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->label('فعال')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('grade_id')
                    ->label('پایه')
                    ->relationship('grade', 'title'),

                TernaryFilter::make('is_active')
                    ->label('وضعیت فعال بودن'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('ویرایش'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ]);
    }
}
