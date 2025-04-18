<?php

namespace App\Interfaces;
use App\Models\Grade;
use App\Models\AcademicYear;

interface GradeRepositoryInterface
{
    public function findAll();
    public function get(Grade $grade);
    public function findById(int $id);
    public function create(array $attributes);
    public function update(Grade $grade, array $attributes);
    public function delete(Grade $grade);
    public function restore(int $id);
    public function getYearGrades(AcademicYear $academicYear);
    public function archive();
}
