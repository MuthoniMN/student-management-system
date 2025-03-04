<?php

namespace App\Services;

use App\Interfaces\ResultRepositoryInterface;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Student;

class PDFService
{
    public function __construct(
        protected ResultRepositoryInterface $resultRepository
    ) {}

    public function gradeSemester(Grade $grade, Semester $semester){
        return [
            'results' => $this->resultRepository->getGradeSemesterResults($grade, $semester),
            'semester' => $semester,
            'year' => [],
            'grade' => $grade
        ];
    }

    public function gradeYear(Grade $grade, AcademicYear $academicYear){
        return [
            'results' => $this->resultRepository->getGradeYearResults($grade, $academicYear),
            'semester' => [],
            'year' => $academicYear,
            'grade' => $grade
        ];
    }

    public function studentSemester(Student $student, Semester $semester){
        return [
            'id' => $student->id,
            'studentId' => $student->studentId,
            'name' => $student->name,
            'results' => $this->resultRepository->getStudentSemesterResults($student, $semester)
        ];
    }

    public function studentYear(Student $student, AcademicYear $academicYear){
        $results = $this->resultRepository->getStudentYearResults($student, $academicYear);

        $ranks = $this->resultRepository->getStudentYearRanks($student, $academicYear);
        return [
            'results' => $results,
            'ranks' => $ranks,
            'student' => $student,
            'year' => $academicYear,
            'subjects' => ['Mathematics', 'English', 'Kiswahili', 'Science', 'Geography', 'CRE', 'History', 'Computer']
        ];
    }
}
