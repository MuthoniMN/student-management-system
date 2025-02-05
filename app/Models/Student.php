<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'grade_id',
        'studentId'
    ];

    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(ParentData::class);
    }

    public function results(): HasMany {
        return $this->hasMany(Result::class);
    }
}
