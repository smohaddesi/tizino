<?php

namespace App\Filament\Resources\QuestionOptions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('question_id')
                    ->required()
                    ->numeric(),
                TextInput::make('body')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_correct')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
