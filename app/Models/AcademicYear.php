<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'year',
        'start_date',
        'end_date'
    ];

    public function semesters(): HasMany {
        return $this->hasMany(Semester::class);
    }
}
