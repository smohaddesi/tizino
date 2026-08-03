<?php

namespace App\Filament\Resources\Grades\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان پایه')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('مثلاً: پایه ششم')
                    ->autocomplete(false)
                    ->columnSpanFull(),
            ]);
    }
}