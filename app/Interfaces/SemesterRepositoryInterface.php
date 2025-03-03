<?php

namespace App\Interfaces;

use App\Models\Semester;
use App\Models\Student;

interface SemesterRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function find(Semester $semester);
    public function findById(int $id);
    public function update(Semester $semester, array $attributes);
    public function getStudentSemesters(Student $student);
    public function delete(Semester $semester);
    public function restore(int $id);
    public function archive();
}
