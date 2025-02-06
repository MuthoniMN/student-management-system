<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'title',
        'file',
        'type',
        'exam_date',
        'grade_id',
        'subject_id',
        'semester_id'
    ];

    public function subject(): BelongsTo {
        return $this->belongsTo(Subject::class);
    }

    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function results(): HasMany {
        return $this->hasMany(Result::class);
    }

    public function semester(): BelongsTo {
        return $this->belongsTo(Semester::class);
    }
}
