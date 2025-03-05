<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    /** @use HasFactory<\Database\Factories\GradeFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function students(): HasMany {
        return $this->hasMany(Student::class);
    }

    public function exams(): HasMany {
        return $this->hasMany(Exam::class);
    }

}
