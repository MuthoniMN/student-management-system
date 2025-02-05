<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

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
