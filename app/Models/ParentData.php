<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentData extends Model
{
    use SoftDeletes;

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
        return $this->hasMany(Student::class);
    }
}
