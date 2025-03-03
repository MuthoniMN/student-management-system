<?php

namespace App\Interfaces;
use App\Models\Student;
use App\Models\Result;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\Exam;

interface ResultRepositoryInterface
{
    public function findAll();
    public function get(Result $result);
    public function findById(int $id);
    public function create(array $attributes);
    public function createMany(array $results);
    public function findExamResults(Exam $exam);
    public function getStudentSemesterResults(Student $student, Semester $semester);
    public function getResults();
    public function getStudentSemesterRanks(Student $student, Semester $semester);
    public function getStudentYearResults(Student $student, AcademicYear $academicYear);
    public function getStudentYearRanks(Student $student, AcademicYear $academicYear);
    public function getGradeSemesterResults(Grade $grade, Semester $semester);
    public function getGradeSemesterRanks(Grade $grade, Semester $semester);
    public function getGradeYearResults(Grade $grade, AcademicYear $academicYear);
    public function getGradeYearRanks(Grade $grade, AcademicYear $academicYear);
    public function update(Result $result, array $attributes);
    public function delete(Result $result);
    public function restore(int $id);
}
