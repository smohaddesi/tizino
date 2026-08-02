<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'title',
        'description',
        'duration_minutes',
        'total_questions',
        'total_score',
        'max_attempts',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class)
            ->orderBy('question_number');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class)
            ->latest();
    }
}