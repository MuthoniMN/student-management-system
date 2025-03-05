<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'result',
        'grade',
        'student_id',
        'exam_id'
    ];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo {
        return $this->belongsTo(Exam::class);
    }
}
