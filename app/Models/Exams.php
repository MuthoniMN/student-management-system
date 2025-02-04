<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exams extends Model
{
    protected $fillable = [
        'title',
        'file',
        'grade_id',
        'subject_id'
    ];

    public function subject(): BelongsTo {
        return $this->belongsTo(Subject::class);
    }

    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function results(): HasMany {
        return $this->hasMany(Result::class);
    }
}
