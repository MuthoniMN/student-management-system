<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ParentData;
use App\Models\Student;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'studentId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function getAuthIdentifierName()
    {
        return 'student_id_or_email'; // Custom identifier
    }

    public function findForPassport($login)
    {
        return $this->where('studentId', $login)
                    ->orWhere('email', $login)
                    ->first();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentData::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isParent(): bool
    {
        return $this->type === UserRole::PARENT->value || $this->parent_id !== null;
    }

    public function isStudent(): bool
    {
        return $this->type === UserRole::STUDENT->value || $this->student_id !== null;
    }
}
