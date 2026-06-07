<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRecordStudent extends Model
{
    protected $fillable = [
        'class_record_id',
        'student_id',
        'initial_grade',
        'quarterly_grade',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'initial_grade' => 'decimal:2',
            'quarterly_grade' => 'integer',
        ];
    }

    public function classRecord(): BelongsTo
    {
        return $this->belongsTo(ClassRecord::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function assessmentScores(): HasMany
    {
        return $this->hasMany(AssessmentScore::class);
    }
}
