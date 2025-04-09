<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Student;
use App\Models\ParentData;
use App\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function createStudentAccount(Student $student): User
    {
        $user = new User;
        $user->studentId = $student->studentId;
        $user->name = $student->name;
        $user->student_id = $student->id;
        $user->password = $student->studentId;
        $user->role = 'student';
        $user->save();

        return $user;
    }

    public function createParentAccount(ParentData $parent): User
    {
        $user = new User;
        $user->email = $parent->email;
        $user->name = $parent->name;
        $user->parent_id = $parent->id;
        $user->role = 'parent';
        $user->password = "";
        $user->save();

        return $user;
    }
}
