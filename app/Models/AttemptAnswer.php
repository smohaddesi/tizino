<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_attempt_id',
        'exam_question_id',
        'question_option_id',
        'is_correct',
        'earned_score',
        'time_spent',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'is_correct' => 'boolean',
        'earned_score' => 'float',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(
            ExamAttempt::class,
            'exam_attempt_id'
        );
    }

    public function examQuestion(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(
            QuestionOption::class,
            'question_option_id'
        );
    }
}