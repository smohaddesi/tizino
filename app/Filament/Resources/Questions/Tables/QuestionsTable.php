<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('body')
                    ->label('متن سؤال')
                    ->limit(60)
                    ->searchable(),

                TextColumn::make('topic.title')
                    ->label('موضوع')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('topic.subject.title')
                    ->label('درس')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                ImageColumn::make('image')
                    ->label('تصویر'),

                TextColumn::make('difficulty')
                    ->label('سختی')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('answer_time')
                    ->label('زمان پاسخ (ثانیه)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('source')
                    ->label('منبع')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('فعال')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('topic_id')
                    ->label('موضوع')
                    ->relationship('topic', 'title'),

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
