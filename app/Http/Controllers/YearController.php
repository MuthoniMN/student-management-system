<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\YearRequest;
use App\Http\Requests\YearUpdateRequest;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Year/List', [
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Year/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(YearRequest $request)
    {
        $validated = $request->validated();

        $year = AcademicYear::create($validated);

        return redirect(route('years.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        return Inertia::render('Year/Show', [
            'year' => $academicYear,
            'grades' => Grade::all(),
            'semesters' => DB::table('semesters')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->select('semesters.*', 'academic_years.year as year')
                ->where('semesters.academic_year_id', $academicYear->id)
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
                ->where('semesters.academic_year_id', $academicYear->id)
                ->where('results.deleted_at', null)
                ->select('results.*', 'students.name as student', 'grades.id as grade_id', 'grades.name as class_grade', 'semesters.title as semester', 'semesters.id as semester_id', 'academic_years.year', 'subjects.id as subject_id', 'subjects.title as subject', 'exams.type', 'exams.subject_id')
                ->get(),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return Inertia::render('Year/Edit', [
            'year' => $academicYear->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(YearUpdateRequest $request, AcademicYear $academicYear)
    {
        $validated = $request->validated();

        $academicYear->fill($validated);

        if($academicYear->isDirty()){
            $academicYear->save();
        }

        return back()->with('update', 'Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return back()->with('delete', "Deleted!");
    }
}
