<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Grade;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('اطلاعات سؤال')
                    ->columns(3)
                    ->schema([
                        Select::make('grade_filter')
                            ->label('پایه')
                            ->options(fn () => Grade::query()->pluck('title', 'id'))
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Set $set) => $set('subject_filter', null))
                            ->afterStateHydrated(function (Select $component, ?Question $record) {
                                if ($record?->topic?->subject?->grade_id) {
                                    $component->state($record->topic->subject->grade_id);
                                }
                            }),

                        Select::make('subject_filter')
                            ->label('درس')
                            ->options(fn (Get $get) => Subject::query()
                                ->when($get('grade_filter'), fn ($q, $gradeId) => $q->where('grade_id', $gradeId))
                                ->pluck('title', 'id'))
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn (Set $set) => $set('topic_id', null))
                            ->afterStateHydrated(function (Select $component, ?Question $record) {
                                if ($record?->topic?->subject_id) {
                                    $component->state($record->topic->subject_id);
                                }
                            }),

                        Select::make('topic_id')
                            ->label('موضوع')
                            ->options(fn (Get $get) => Topic::query()
                                ->when($get('subject_filter'), fn ($q, $subjectId) => $q->where('subject_id', $subjectId))
                                ->pluck('title', 'id'))
                            ->searchable()
                            ->live()
                            ->required(),

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

                        Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true)
                            ->required()
                            ->columnSpan(3),
                    ]),

                Section::make('متن سؤال')
                    ->columns(2)
                    ->schema([
                        Textarea::make('body')
                            ->label('متن سؤال')
                            ->required()
                            ->rows(6)
                            ->live(onBlur: true),

                        Placeholder::make('preview')
                            ->label('پیش‌نمایش زنده')
                            ->content(function (Get $get) {
                                $body = trim((string) $get('body'));
                                $options = $get('options') ?? [];

                                $html = '<div style="border:1px solid #dee2e6;border-radius:.6rem;padding:1rem;background:#f8f9fa;">';
                                $html .= '<div style="font-weight:700;margin-bottom:.75rem;">'
                                    .($body !== '' ? e($body) : '— هنوز متنی وارد نشده —')
                                    .'</div>';

                                if (empty($options)) {
                                    $html .= '<div style="color:#6c757d;font-size:.85rem;">هنوز گزینه‌ای اضافه نشده.</div>';
                                } else {
                                    foreach ($options as $option) {
                                        $text = trim((string) ($option['body'] ?? ''));
                                        $isCorrect = (bool) ($option['is_correct'] ?? false);

                                        $style = $isCorrect
                                            ? 'background:#d1e7dd;color:#0f5132;border:1px solid #a3cfbb;'
                                            : 'background:#fff;border:1px solid #dee2e6;';

                                        $html .= '<div style="display:flex;align-items:center;gap:.5rem;padding:.45rem .65rem;margin-bottom:.4rem;border-radius:.4rem;'.$style.'">';
                                        $html .= $isCorrect ? '✅' : '⬜';
                                        $html .= '<span>'.($text !== '' ? e($text) : '—').'</span>';
                                        $html .= '</div>';
                                    }
                                }

                                $html .= '</div>';

                                return new HtmlString($html);
                            }),

                        Placeholder::make('duplicate_warning')
                            ->label('')
                            ->columnSpanFull()
                            ->content(function (Get $get, ?Question $record) {
                                $body = trim((string) $get('body'));
                                $topicId = $get('topic_id');

                                if ($body === '' || mb_strlen($body) < 10 || ! $topicId) {
                                    return null;
                                }

                                $similar = Question::query()
                                    ->where('topic_id', $topicId)
                                    ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
                                    ->get()
                                    ->first(function (Question $question) use ($body) {
                                        similar_text($question->body, $body, $percent);

                                        return $percent > 80;
                                    });

                                if (! $similar) {
                                    return null;
                                }

                                return new HtmlString(
                                    '<div style="background:#fff3cd;border:1px solid #ffe69c;color:#664d03;padding:.6rem .85rem;border-radius:.5rem;font-size:.85rem;">'
                                    .'⚠️ یه سؤال مشابه از قبل توی همین موضوع هست: «'.e(Str::limit($similar->body, 70)).'»'
                                    .'</div>'
                                );
                            }),

                        FileUpload::make('image')
                            ->label('تصویر سؤال (اختیاری)')
                            ->image()
                            ->columnSpanFull(),

                        Textarea::make('answer_explanation')
                            ->label('توضیح پاسخ (اختیاری)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('گزینه‌های سؤال')
                    ->schema([
                        Repeater::make('options')
                            ->relationship('options')
                            ->label('')
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->minItems(2)
                            ->maxItems(8)
                            ->defaultItems(4)
                            ->addActionLabel('افزودن گزینه')
                            ->itemLabel(fn (array $state): ?string => $state['body'] ?? null)
                            ->live(onBlur: true)
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    $correctCount = collect($value)
                                        ->filter(fn ($item) => $item['is_correct'] ?? false)
                                        ->count();

                                    if ($correctCount !== 1) {
                                        $fail("باید دقیقاً یک گزینه به‌عنوان پاسخ صحیح انتخاب بشه (الان: {$correctCount} گزینه مشخص شده).");
                                    }
                                },
                            ])
                            ->schema([
                                TextInput::make('body')
                                    ->label('متن گزینه')
                                    ->required()
                                    ->live(onBlur: true),

                                Toggle::make('is_correct')
                                    ->label('پاسخ صحیح')
                                    ->default(false)
                                    ->required()
                                    ->live(),

                                FileUpload::make('image')
                                    ->label('تصویر گزینه (اختیاری)')
                                    ->image(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->helperText('دقیقاً یکی از گزینه‌ها باید به‌عنوان «پاسخ صحیح» مشخص بشه — سیستم قبل از ذخیره چک می‌کنه.'),
                    ]),
            ]);
    }
}
