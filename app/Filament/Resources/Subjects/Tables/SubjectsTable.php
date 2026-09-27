<?php

namespace App\Filament\Resources\Subjects\Tables;

use App\Support\JalaliDate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('grade.title')
                    ->label('پایه')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('عنوان درس')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('sort_order')
                    ->label('ترتیب')
                    ->formatStateUsing(fn ($state) => JalaliDate::toPersianDigits($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
