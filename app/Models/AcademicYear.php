<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    /** @use HasFactory<\Database\Factories\AcademicYearFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'year',
        'start_date',
        'end_date'
    ];

    public function semesters(): HasMany {
        return $this->hasMany(Semester::class);
    }
}
