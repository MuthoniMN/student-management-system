<?php

namespace App\Interfaces;

use App\Models\Student;
use App\Models\ParentData;

interface UserRepositoryInterface
{
    public function createStudentAccount(Student $student);
    public function createParentAccount(ParentData $parent);
}
