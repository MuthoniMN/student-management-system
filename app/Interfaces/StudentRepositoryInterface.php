<?php

namespace App;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Result

interface StudentRepositoryInterface
{
    public function getAllStudents();
    public function getStudentById(Student $student);
    public function updateStudent(Student $student, array $attributes);
    public function updateStudentsGrade(array $attribute, Grade $grade);
    public function getStudentsYearlyResults(Student $student, AcademicYear $academicYear);
    public function getStudentsSemesterResults(Student $student, Semester $semester);
    public function createStudent(array $attributes);
    public function deleteStudent(Student $student);
    public function deleteManyStudents(array $attributes);
    public function restoreStudent(Student $student);
    public function restoreManyStudents(array $attributes);
}
