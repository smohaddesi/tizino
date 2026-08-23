<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Filament\Resources\Questions\Pages\EditQuestion;
use App\Models\Question;
use App\Models\Subject;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('body')
                    ->label('متن سؤال')
                    ->limit(60)
                    ->searchable(),

                TextColumn::make('topic.subject.title')
                    ->label('درس')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('topic.title')
                    ->label('موضوع')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image')
                    ->label('تصویر'),

                TextColumn::make('difficulty')
                    ->label('سختی')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('answer_time')
                    ->label('زمان پاسخ (ثانیه)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('source')
                    ->label('منبع')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->label('فعال'),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('subject_id')
                    ->label('درس')
                    ->options(fn () => Subject::query()->pluck('title', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $subjectId) => $q->whereHas(
                                'topic',
                                fn (Builder $q) => $q->where('subject_id', $subjectId)
                            )
                        );
                    }),

                SelectFilter::make('topic_id')
                    ->label('موضوع')
                    ->relationship('topic', 'title')
                    ->searchable(),

                SelectFilter::make('difficulty')
                    ->label('سطح سختی')
                    ->options([
                        1 => '۱ (خیلی آسان)',
                        2 => '۲ (آسان)',
                        3 => '۳ (متوسط)',
                        4 => '۴ (سخت)',
                        5 => '۵ (خیلی سخت)',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('وضعیت فعال بودن'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('ویرایش'),

                Action::make('duplicate')
                    ->label('کپی')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('کپی این سؤال؟')
                    ->modalDescription('یه نسخه‌ی مشابه از این سؤال (همراه با گزینه‌هاش) ساخته می‌شه تا سریع‌تر ویرایشش کنی.')
                    ->action(function (Question $record) {
                        $clone = $record->replicate();
                        $clone->body = $record->body.' (کپی)';
                        $clone->save();

                        foreach ($record->options as $option) {
                            $newOption = $option->replicate();
                            $newOption->question_id = $clone->id;
                            $newOption->save();
                        }

                        Notification::make()
                            ->title('سؤال کپی شد')
                            ->success()
                            ->send();

                        return redirect(EditQuestion::getUrl(['record' => $clone]));
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف'),
                ]),
            ]);
    }
}
