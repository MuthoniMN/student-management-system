<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function exams(): HasMany {
        return $this->hasMany(Exam::class);
    }
}
