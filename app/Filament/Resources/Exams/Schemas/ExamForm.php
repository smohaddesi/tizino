<?php

namespace App\Filament\Resources\Exams\Schemas;

use App\Filament\Forms\Components\JalaliDateTimePicker;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

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

                JalaliDateTimePicker::make('start_at')
                    ->label('شروع بازه فعال بودن')
                    ->seconds(false)
                    ->rule(function ($livewire) {
                        return function (string $attribute, $value, Closure $fail) use ($livewire) {
                            if (blank($value)) {
                                return;
                            }

                            if (! ($livewire instanceof CreateRecord)) {
                                return;
                            }

                            if (Carbon::parse($value)->isPast()) {
                                $fail('شروع بازه فعال بودن نباید در گذشته باشد.');
                            }
                        };
                    }),

                JalaliDateTimePicker::make('end_at')
                    ->label('پایان بازه فعال بودن')
                    ->seconds(false)
                    ->rule(function (Get $get) {
                        return function (string $attribute, $value, Closure $fail) use ($get) {
                            $start = $get('start_at');

                            if (blank($start) || blank($value)) {
                                return;
                            }

                            if (! Carbon::parse($value)->gt(Carbon::parse($start))) {
                                $fail('پایان بازه فعال بودن باید بعد از شروع بازه فعال بودن باشد.');
                            }
                        };
                    }),

                Toggle::make('is_active')
                    ->label('فعال')
                    ->default(true)
                    ->required(),
            ]);
    }
}