<?php

namespace App\Filament\Resources\Grades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GradesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام پایه')
                    ->searchable(),

                TextColumn::make('code')
                    ->label('کد پایه')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->jalaliDateTime(),

                TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->jalaliDateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
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