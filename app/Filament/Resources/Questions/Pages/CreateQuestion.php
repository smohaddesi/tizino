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
        if ($topicId = session('last_question_topic_id')) {
            $topic = Topic::find($topicId);

            if ($topic) {
                $data['topic_id'] = $topic->id;
                $data['subject_filter'] = $topic->subject_id;
                $data['grade_filter'] = $topic->subject?->grade_id;
            }

            $data['difficulty'] = session('last_question_difficulty', 2);
            $data['answer_time'] = session('last_question_answer_time', 75);
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
