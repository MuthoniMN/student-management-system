<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
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
}
