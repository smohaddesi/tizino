<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use App\Support\JalaliDate;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewExamAttempt extends ViewRecord
{
    protected static string $resource = ExamAttemptResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->loadMissing([
            'user',
            'exam',
            'answers.option',
            'answers.examQuestion.question.options',
        ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('اطلاعات کلی')
                    ->schema([
                        TextEntry::make('user.name')->label('دانش‌آموز'),
                        TextEntry::make('exam.title')->label('آزمون'),
                        TextEntry::make('started_at')->label('شروع')->formatStateUsing(fn ($state) => JalaliDate::format($state)),
                        TextEntry::make('submitted_at')->label('پایان')->formatStateUsing(fn ($state) => JalaliDate::format($state))->placeholder('—'),
                        TextEntry::make('is_finished')
                            ->label('وضعیت')
                            ->badge()
                            ->formatStateUsing(fn (bool $state) => $state ? 'پایان‌یافته' : 'در حال انجام')
                            ->color(fn (bool $state) => $state ? 'success' : 'warning'),
                        TextEntry::make('score')->label('نمره'),
                        TextEntry::make('correct_answers')->label('پاسخ درست'),
                        TextEntry::make('wrong_answers')->label('پاسخ غلط'),
                        TextEntry::make('blank_answers')->label('بدون پاسخ'),
                    ])
                    ->columns(3),

                Section::make('مرور پاسخ‌ها')
                    ->schema([
                        RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                TextEntry::make('examQuestion.question_number')
                                    ->label('شماره سؤال'),

                                TextEntry::make('examQuestion.question.body')
                                    ->label('متن سؤال')
                                    ->columnSpanFull(),

                                TextEntry::make('option.body')
                                    ->label('پاسخ دانش‌آموز')
                                    ->placeholder('بدون پاسخ'),

                                TextEntry::make('correct_option_body')
                                    ->label('پاسخ صحیح')
                                    ->state(fn ($record) => $record->examQuestion?->question?->options
                                        ->firstWhere('is_correct', true)?->body ?? '—'),

                                IconEntry::make('is_correct')
                                    ->label('نتیجه')
                                    ->boolean(),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}
