<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Services\PDFService;
use Illuminate\Support\Facades\Gate;

class PDFController extends Controller
{
    public function __construct(
        protected PDFService $pdfService
    ) {}

    public function gradeSemesterResults(Semester $semester, Grade $grade){
        $dependencies = $this->pdfService->gradeSemester($grade, $semester);

        $pdf = Pdf::loadView('pdf.template', $dependencies);

        return $pdf->download("{$semester->year->year} -$semester->title $grade->name Results");
    }

    public function gradeYearResults(AcademicYear $academicYear, Grade $grade){
        $dependencies = $this->pdfService->gradeYear($grade, $academicYear);

        $pdf = Pdf::loadView('pdf.template', $dependencies);

        return $pdf->download("$academicYear->year $grade->name Results");
    }

    public function studentResults(Student $student, Semester $semester){
        Gate::authorize('view', $student);
        $results = $this->pdfService->studentSemester($student, $semester);

        $pdf = Pdf::loadView('pdf.student', [
                'results' => $results,
            ]);

        return $pdf->download("$student->name Results for $semester->title");
    }

    public function studentYearlyResults(Student $student, AcademicYear $academicYear){
        Gate::authorize('view', $student);
        $dependencies = $this->pdfService->studentYear($student, $academicYear);

        $pdf = Pdf::loadView('pdf.year', $dependencies);

        return $pdf->download("$student->name Results for $academicYear->year");
    }
}
