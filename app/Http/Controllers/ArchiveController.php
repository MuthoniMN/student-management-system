<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\ParentData;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Grade;
use App\Models\Semester;

class ArchiveController extends Controller
{
    public function index(){
        return Inertia::render('Archive/Index');
    }

    public function semesterArchive(){
        return Inertia::render('Archive/Semester', [
            'years' => AcademicYear::all(),
            'semesters' => Semester::onlyTrashed()->get(),
        ]);
    }

    public function studentArchive(){
        $students = Student::onlyTrashed()->with(['parent', 'grade'])->get();

        return Inertia::render('Archive/Student', [
            "students" => $students,
            "grades" => Grade::all(),
            "parents" => ParentData::all()
        ]);
    }

    public function subjectArchive(){
        return Inertia::render('Archive/Subject', [
            'grades' => Grade::all(),
            'subjects' => Subject::onlyTrashed()->get()
        ]);
    }

    public function gradeArchive(){
        return Inertia::render('Archive/Grade', [
            'grades' => Grade::onlyTrashed('students')->get(),
        ]);
    }

    public function examsArchive(){
        return Inertia::render('Archive/Exam', [
            'exams' => Exam::onlyTrashed()->get(),
            'grades' => Grade::all(),
            'semesters' => Semester::with('year')->get(),
        ]);
    }

    public function resultsArchive(){
        return Inertia::render('Archive/Results', [
            'grades' => Grade::all(),
            'semesters' => Semester::all(),
            'subjects' => Subject::all(),
            'students' => Student::all(),
            'results' => Result::onlyTrashed(['exam', 'student', 'exam.grade', 'exam.subject', 'exam.semester', 'exam.semester.year'])->get(),
        ]);

    }
}
