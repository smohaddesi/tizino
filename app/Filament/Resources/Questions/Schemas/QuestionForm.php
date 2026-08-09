<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Topic;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('topic_id')
                    ->label('موضوع')
                    ->options(
                        fn () => Topic::query()
                            ->with('subject')
                            ->get()
                            ->mapWithKeys(fn (Topic $topic) => [
                                $topic->id => ($topic->subject?->title ?? '-').' » '.$topic->title,
                            ])
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Textarea::make('body')
                    ->label('متن سؤال')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('تصویر سؤال (اختیاری)')
                    ->image(),

                TextInput::make('difficulty')
                    ->label('سطح سختی (۱ تا ۵)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->default(2),

                TextInput::make('answer_time')
                    ->label('زمان پاسخ (ثانیه)')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(75),

                TextInput::make('source')
                    ->label('منبع سؤال (اختیاری)'),

                Textarea::make('answer_explanation')
                    ->label('توضیح پاسخ (اختیاری)')
                    ->rows(2)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('فعال')
                    ->default(true)
                    ->required(),

                Repeater::make('options')
                    ->relationship('options')
                    ->label('گزینه‌های سؤال')
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->minItems(2)
                    ->maxItems(8)
                    ->defaultItems(4)
                    ->addActionLabel('افزودن گزینه')
                    ->itemLabel(fn (array $state): ?string => $state['body'] ?? null)
                    ->schema([
                        TextInput::make('body')
                            ->label('متن گزینه')
                            ->required(),

                        Toggle::make('is_correct')
                            ->label('پاسخ صحیح')
                            ->default(false)
                            ->required(),

                        FileUpload::make('image')
                            ->label('تصویر گزینه (اختیاری)')
                            ->image(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->helperText('حداقل یکی از گزینه‌ها باید به‌عنوان «پاسخ صحیح» مشخص شود.'),
            ]);
    }
}
