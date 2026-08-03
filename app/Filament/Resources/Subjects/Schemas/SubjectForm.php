<?php

namespace App\Filament\Resources\Subjects\Schemas;

use App\Models\Grade;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grade_id')
                    ->label('پایه')
                    ->relationship('grade', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('title')
                    ->label('عنوان درس')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('مثلاً: ریاضی')
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('ترتیب نمایش')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}