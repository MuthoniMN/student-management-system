<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    /** @use HasFactory<\Database\Factories\SemesterFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'academic_year_id',
        'title',
        'start_date',
        'end_date'
    ];

    public function year(): BelongsTo {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function exams(): HasMany {
        return $this->hasMany(Exam::class);
    }
}
