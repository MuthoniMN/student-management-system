<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function exams(): HasMany {
        return $this->hasMany(Exam::class);
    }
}
