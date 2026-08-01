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
                TextInput::make('name')
                    ->label('نام پایه')
                    ->required()
                    ->maxLength(50),

                TextInput::make('code')
                    ->label('کد پایه')
                    ->numeric()
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }
}