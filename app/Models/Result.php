<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes;

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
