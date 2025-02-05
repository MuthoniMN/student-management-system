<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Semester extends Model
{
    protected $fillable = [
        'academic_year_id',
        'title',
        'start_date',
        'end_date'
    ];

    public function year(): BelongsTo {
        return $this->belongsTo(AcademicYear::class);
    }

    public function exams(): HasMany {
        return $this->hasMany(Exam::class);
    }
}
