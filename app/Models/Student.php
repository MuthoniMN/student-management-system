<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'grade_id',
        'studentId'
    ];

    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(ParentData::class);
    }

    public function results(): HasMany {
        return $this->hasMany(Result::class);
    }

    public function exam_results(){
        return $this->hasManyThrough(Exam::class, Result::class, 'student_id', 'id', 'id', 'exam_id');
    }
}
