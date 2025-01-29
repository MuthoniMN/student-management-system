<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'grade_id'
    ];

    public function grade(): HasOne {
        return $this->hasOne(Grade::class);
    }

    public function parent(): HasOne {
        return $this->hasOne(ParentData::class);
    }
}
