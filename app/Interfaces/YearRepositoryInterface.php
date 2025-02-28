<?php

namespace App\Interfaces;

use App\Models\AcademicYear;
use App\Models\Student;

interface YearRepositoryInterface
{
    public function create(array $attributes);
    public function findAll();
    public function find(AcademicYear $academicYear);
    public function findById(int $id);
    public function update(AcademicYear $academicYear, array $attributes);
    public function getStudentYears(Student $student);
    public function delete(AcademicYear $academicYear);
    public function restore(int $id);
}
