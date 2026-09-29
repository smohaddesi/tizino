<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Question;
use App\Models\Topic;
use App\Support\JalaliDate;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;
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
            ->columns(1)
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(['default' => 1, 'md' => 12])
                    ->schema([
                        Select::make('topic_id')
                            ->label('موضوع')
                            ->options(fn () => self::groupedTopicOptions())
                            ->searchable()
                            ->required()
                            ->columnSpan(['md' => 8]),

                        ToggleButtons::make('difficulty')
                            ->label('سطح سختی')
                            ->options(collect(range(1, 5))->mapWithKeys(
                                fn (int $level) => [$level => JalaliDate::toPersianDigits((string) $level)]
                            )->all())
                            ->inline()
                            ->grouped()
                            ->required()
                            ->default(2)
                            ->helperText('۱ = خیلی آسان ... ۵ = خیلی سخت')
                            ->columnSpan(['md' => 4]),

                        Textarea::make('body')
                            ->label('متن سؤال')
                            ->required()
                            ->rows(4)
                            ->autofocus()
                            ->live(onBlur: true)
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('تصویر سؤال (اختیاری)')
                            ->image()
                            ->panelLayout('compact')
                            ->columnSpanFull(),

                        Placeholder::make('duplicate_warning')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->content(function (Get $get, ?Question $record) {
                                $similar = self::findSimilarQuestion(
                                    (string) $get('body'),
                                    $get('topic_id'),
                                    $record,
                                );

                                if (! $similar) {
                                    return null;
                                }

                                return new HtmlString(
                                    '<div style="background:#fff3cd;border:1px solid #ffe69c;color:#664d03;padding:.6rem .85rem;border-radius:.5rem;font-size:.85rem;">'
                                    .'⚠️ یه سؤال مشابه از قبل توی همین موضوع هست: «'.e(Str::limit($similar->body, 70)).'»'
                                    .'</div>'
                                );
                            }),
                    ]),

                Repeater::make('options')
                    ->relationship('options')
                    ->label('گزینه‌ها')
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->minItems(2)
                    ->maxItems(8)
                    ->defaultItems(4)
                    ->addActionLabel('افزودن گزینه')
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            $correctCount = collect($value)
                                ->filter(fn ($item) => $item['is_correct'] ?? false)
                                ->count();

                            if ($correctCount !== 1) {
                                $fail('باید دقیقاً یک گزینه به‌عنوان پاسخ صحیح انتخاب بشه ('.JalaliDate::toPersianDigits((string) $correctCount).' گزینه مشخص شده).');
                            }
                        },
                    ])
                    ->schema([
                        TextInput::make('body')
                            ->label('متن گزینه')
                            ->required()
                            ->columnSpan(['md' => 6]),

                        FileUpload::make('image')
                            ->label('تصویر (اختیاری)')
                            ->image()
                            ->panelLayout('compact')
                            ->columnSpan(['md' => 4]),

                        Toggle::make('is_correct')
                            ->label('صحیح')
                            ->inline(false)
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set, Component $component) {
                                if (! $state) {
                                    return;
                                }

                                $segments = explode('.', $component->getStatePath());
                                $currentKey = $segments[count($segments) - 2] ?? null;

                                foreach (array_keys($get('../../options') ?? []) as $key) {
                                    if ((string) $key !== (string) $currentKey) {
                                        $set('../../options.'.$key.'.is_correct', false);
                                    }
                                }
                            })
                            ->columnSpan(['md' => 2]),
                    ])
                    ->columns(['default' => 1, 'md' => 12])
                    ->columnSpanFull(),

                Section::make('پاسخ تشریحی سؤال')
                    ->columnSpanFull()
                    ->description('اختیاری — بعد از «نمایش پاسخ» به دانش‌آموز نشون داده می‌شه.')
                    ->columns(['default' => 1, 'md' => 12])
                    ->schema([
                        Textarea::make('answer_explanation')
                            ->label('متن پاسخ تشریحی')
                            ->rows(5)
                            ->columnSpanFull(),

                        FileUpload::make('answer_explanation_image')
                            ->label('تصویر پاسخ تشریحی (اختیاری)')
                            ->image()
                            ->panelLayout('compact')
                            ->columnSpanFull(),
                    ]),

                Section::make('تنظیمات بیشتر')
                    ->columnSpanFull()
                    ->description('زمان پاسخ، منبع، وضعیت انتشار')
                    ->collapsible()
                    ->collapsed(fn (?Question $record) => ! ($record && filled($record->source)))
                    ->columns(['default' => 1, 'md' => 3])
                    ->schema([
                        TextInput::make('answer_time')
                            ->label('زمان پاسخ (ثانیه — اختیاری)')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('source')
                            ->label('منبع سؤال (اختیاری)')
                            ->columnSpan(['md' => 2]),

                        Toggle::make('is_active')
                            ->label('فعال')
                            ->default(true)
                            ->required(),

                        Toggle::make('is_free_sample')
                            ->label('نمونه‌ی رایگان (بدون اشتراک هم قابل مشاهده باشه)')
                            ->default(false)
                            ->columnSpan(['md' => 2]),
                    ]),

                Section::make('پیش‌نمایش')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Placeholder::make('preview')
                            ->hiddenLabel()
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
                    ]),
            ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    private static function groupedTopicOptions(): array
    {
        return Topic::query()
            ->with('subject.grade')
            ->get()
            ->sortBy(fn (Topic $topic) => sprintf(
                '%05d-%05d-%05d',
                $topic->subject?->grade_id ?? 0,
                $topic->subject_id,
                $topic->id,
            ))
            ->groupBy(fn (Topic $topic) => ($topic->subject?->grade?->title ?? '—').' › '.($topic->subject?->title ?? '—'))
            ->map(fn ($topics) => $topics->pluck('title', 'id')->all())
            ->all();
    }

    private static function findSimilarQuestion(string $body, mixed $topicId, ?Question $record): ?Question
    {
        $body = trim($body);

        if (mb_strlen($body) < 10 || ! $topicId) {
            return null;
        }

        $needle = mb_substr($body, 0, 300);
        $needleLength = strlen($needle);

        return Question::query()
            ->select(['id', 'body'])
            ->where('topic_id', $topicId)
            ->when($record, fn ($q) => $q->whereKeyNot($record->getKey()))
            ->latest('id')
            ->limit(300)
            ->get()
            ->first(function (Question $question) use ($needle, $needleLength) {
                $candidate = mb_substr((string) $question->body, 0, 300);
                $candidateLength = strlen($candidate);

                if ($candidateLength === 0 || (2 * min($needleLength, $candidateLength)) / ($needleLength + $candidateLength) <= 0.8) {
                    return false;
                }

                similar_text($candidate, $needle, $percent);

                return $percent > 80;
            });
    }
}
