<?php

namespace App\Filament\Resources\Exams\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'examQuestions';

    protected static ?string $title = 'سؤال‌های آزمون';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('question_id')
                    ->label('سؤال')
                    ->relationship('question', 'body')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('question_number')
                    ->label('شماره سؤال در آزمون')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                TextInput::make('score')
                    ->label('نمره این سؤال')
                    ->numeric()
                    ->minValue(0)
                    ->default(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_number')
            ->defaultSort('question_number')
            ->columns([
                TextColumn::make('question_number')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('question.body')
                    ->label('متن سؤال')
                    ->limit(60)
                    ->searchable(),

                TextColumn::make('score')
                    ->label('نمره')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('افزودن سؤال به آزمون'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('ویرایش'),
                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ]);
    }
}
