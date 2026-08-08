<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExamForm
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
                    ->label('عنوان آزمون')
                    ->required()
                    ->maxLength(200)
                    ->placeholder('مثلاً: آزمون جامع پایه ششم - نوبت اول')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('توضیحات')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('duration_minutes')
                    ->label('مدت زمان (دقیقه)')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                TextInput::make('total_questions')
                    ->label('تعداد سؤال')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('total_score')
                    ->label('نمره کل')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                TextInput::make('max_attempts')
                    ->label('حداکثر تعداد تلاش')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                DateTimePicker::make('start_at')
                    ->label('شروع بازه فعال بودن')
                    ->seconds(false),

                DateTimePicker::make('end_at')
                    ->label('پایان بازه فعال بودن')
                    ->seconds(false)
                    ->after('start_at'),

                Toggle::make('is_active')
                    ->label('فعال')
                    ->default(true)
                    ->required(),
            ]);
    }
}