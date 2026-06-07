<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRecord extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'head_teacher_id',
        'title',
        'ocr_image_path',
        'approval_status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'is_archived',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'is_archived' => 'boolean',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(SubjectTeacherAssignment::class, 'assignment_id');
    }

    public function headTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'head_teacher_id');
    }

    public function classRecordStudents(): HasMany
    {
        return $this->hasMany(ClassRecordStudent::class);
    }
}
