<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function students(): HasMany {
        return $this->hasMany(Student::class);
    }

    public function subjects(): HasMany {
        return $this->hasMany(Subject::class);
    }
}
