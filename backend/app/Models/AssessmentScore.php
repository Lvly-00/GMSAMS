<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentScore extends Model
{
    protected $fillable = [
        'assessment_id',
        'class_record_student_id',
        'raw_score',
        'is_missing',
    ];

    protected function casts(): array
    {
        return [
            'raw_score' => 'decimal:2',
            'is_missing' => 'boolean',
        ];
    }

    public function classRecordStudent(): BelongsTo
    {
        return $this->belongsTo(ClassRecordStudent::class);
    }
}
