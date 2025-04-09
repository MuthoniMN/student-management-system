<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ParentData extends Authenticatable
{
    use SoftDeletes, HasFactory;

    /*
     * The database table
     * */
    protected $table = 'parents';

    /*
     * Modifiable fields
     * */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address'
    ];

    public function children(): HasMany {
        return $this->hasMany(Student::class, 'parent_id', 'student_id');
    }
}
