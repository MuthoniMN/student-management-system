<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Result;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SemesterRequest;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Semester/List', [
            'years' => AcademicYear::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', 'academic_years.id')->where('semesters.deleted_at', null)->select('semesters.*', 'academic_years.year')->orderByDesc('start_date')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Semester/Create', [
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SemesterRequest $request)
    {
        $validated = $request->validated();

        $semester = Semester::create($validated);

        return redirect(route('semesters.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        return Inertia::render('Semester/Show', [
            'semester' => $semester,
            'grades' => Grade::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')->where('semesters.deleted_at', null)->select('semesters.*', 'academic_years.year as year')->get(),
            'years' => AcademicYear::all(),
            'subjects' => Subject::all(),
            'students' => Student::all(),
            'results' => DB::table('results')
                ->join('students', 'results.student_id', '=', 'students.id')
                ->join('exams', 'results.exam_id', '=', 'exams.id')
                ->join('grades', 'exams.grade_id', '=', 'grades.id')
                ->join('semesters', 'exams.semester_id', '=', 'semesters.id')
                ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->where('exams.semester_id', $semester->id)
                ->where('results.deleted_at', null)
                ->select('results.*', 'students.name as student', 'grades.id as grade_id', 'grades.name as class_grade', 'semesters.title as semester', 'semesters.id as semester_id', 'academic_years.year', 'subjects.id as subject_id', 'subjects.title as subject', 'exams.type', 'exams.subject_id', 'exams.title as exam_title')
                ->get(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        return Inertia::render('Semester/Edit', [
            'semester' => $semester,
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, Semester $semester)
    {
        $validated = $request->validated();

        $semester->fill($validated);

        if($semester->isDirty()){
            $semester->save();
        }

        return redirect(route('semesters.index'))->with('update', 'Semester updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect(route('semesters.index'))->with('delete', 'Semester deleted!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $semester = Semester::withTrashed()->where('id', $request->input('id'))->first();
        $semester->restore();

        return redirect(route('semesters.index'))->with('update', 'Semester restored!');
    }

    public function semesterResults(Semester $semester, Grade $grade){
        $results = DB::table('subject_averages')
                ->join('grades as g', 'subject_averages.grade_id', '=', 'g.id')
                ->select(
                    'subject_averages.student_id',
                    'subject_averages.studentId',
                    'subject_averages.student_name',
                    DB::raw('SUM(subject_averages.subject_avg) as total_marks'),
                    DB::raw('RANK() OVER (PARTITION BY g.id ORDER BY SUM(subject_averages.subject_avg) DESC) as rank')
                )
                ->groupBy('subject_averages.student_id', 'subject_averages.studentId', 'subject_averages.student_name', 'g.id')
                ->orderByDesc('total_marks')
                ->fromSub(function ($query) use ($semester, $grade) {
                    $query->from('students as s')
                        ->join('results as r', 's.id', '=', 'r.student_id')
                        ->join('exams as e', 'r.exam_id', '=', 'e.id')
                        ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
                        ->select(
                            's.id as student_id',
                            's.studentId as studentId',
                            's.name as student_name',
                            'e.grade_id as grade_id',
                            DB::raw('ROUND(AVG(r.result)) as subject_avg')
                        )
                        ->where('e.semester_id', $semester->id)
                        ->where('e.grade_id', $grade->id)
                        ->groupBy('s.id', 's.studentId', 's.name', 'sub.id', 'e.grade_id');
                }, 'subject_averages')
                ->get();

        return Inertia::render('Grade/Result', [
            'results' => $results,
            'semester' => $semester,
            'grade' => $grade
        ]);

    }
}
