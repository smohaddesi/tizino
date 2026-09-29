<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Topic;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $topicId = session('last_question_topic_id');

        if ($topicId && Topic::whereKey($topicId)->exists()) {
            $data['topic_id'] = $topicId;
            $data['difficulty'] = session('last_question_difficulty', 2);
            $data['answer_time'] = session('last_question_answer_time');
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        session([
            'last_question_topic_id' => $record->topic_id,
            'last_question_difficulty' => $record->difficulty,
            'last_question_answer_time' => $record->answer_time,
        ]);
    }
}
