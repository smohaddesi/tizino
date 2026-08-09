<?php

namespace App\Filament\Resources\QuestionOptions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('question_id')
                    ->label('سؤال')
                    ->relationship('question', 'body')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('body')
                    ->label('متن گزینه')
                    ->required(),

                FileUpload::make('image')
                    ->label('تصویر گزینه (اختیاری)')
                    ->image(),

                Toggle::make('is_correct')
                    ->label('پاسخ صحیح')
                    ->required(),

                TextInput::make('sort_order')
                    ->label('ترتیب نمایش')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}
