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

class ArchiveController extends Controller
{
    public function index(){
        return Inertia::render('Archive/Index');
    }

    public function semesterArchive(){
        return Inertia::render('Archive/Semester', [
            'years' => AcademicYear::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', 'academic_years.id')->whereNot('semesters.deleted_at', null)->select('semesters.*', 'academic_years.year')->get(),
        ]);
    }

    public function studentArchive(){
        $students = DB::table('students')
                ->join('grades', 'students.grade_id', 'grades.id')
                ->join('parents', 'students.parent_id', 'parents.id')
                ->select('students.*', 'grades.name as grade', 'parents.email', 'parents.phone_number', 'parents.address')
                ->whereNot('students.deleted_at', null)
                ->get();

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
            'exams' => DB::table('exams')
                ->join('grades', 'exams.grade_id', '=', 'grades.id')
                ->join('semesters', 'exams.semester_id', '=', 'semesters.id')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->select('exams.*', 'grades.name as grade', 'semesters.title as semester', 'academic_years.year as year')
                ->whereNot('exams.deleted_at', null)
                ->get(),
            'grades' => Grade::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')->select('semesters.*', 'academic_years.year as year')->where('semesters.deleted_at', null)->get(),
        ]);
    }

    public function resultsArchive(){
        return Inertia::render('Archive/Results', [
            'grades' => Grade::all(),
            'semesters' => DB::table('semesters')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->select('semesters.*', 'academic_years.year as year')
                ->get(),
            'subjects' => Subject::all(),
            'students' => Student::all(),
            'results' => DB::table('results')
                ->join('students', 'results.student_id', '=', 'students.id')
                ->join('exams', 'results.exam_id', '=', 'exams.id')
                ->join('grades', 'exams.grade_id', '=', 'grades.id')
                ->join('semesters', 'exams.semester_id', '=', 'semesters.id')
                ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->whereNot('results.deleted_at', null)
                ->select('results.*', 'students.name as student', 'grades.id as grade_id', 'grades.name as class_grade', 'semesters.title as semester', 'semesters.id as semester_id', 'academic_years.year', 'subjects.id as subject_id', 'subjects.title as subject', 'exams.type', 'exams.subject_id', 'exams.title as exam_title')
                ->get(),
        ]);

    }
}
