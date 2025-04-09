<?php

namespace App\Enums;

enum UserRole: string
{
    case PARENT = 'parent';
    case STUDENT = 'student';
    case TEACHER = 'teacher';
    case ADMIN = 'admin';
}
