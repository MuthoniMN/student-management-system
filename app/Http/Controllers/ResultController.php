<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ResultRequest;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Subject $subject, Exam $exam)
    {
        return Inertia::render('Result/Create', [
            'subject' => $subject,
            'exam' => DB::table('exams')
                ->join('grades', 'exams.grade_id', '=', 'grades.id')
                ->join('semesters', 'exams.semester_id', '=', 'semesters.id')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->select('exams.*', 'grades.name as grade', 'semesters.title as semester', 'academic_years.year')
                ->where('exams.id', $exam->id)->first(),
            'students' => DB::table('students')
                ->join('grades', 'students.grade_id', '=', 'grades.id')
                ->select('students.*', 'grades.name as grade')
                ->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResultRequest $request, Subject $subject, Exam $exam)
    {
        $validated = $request->validated();

        $result = $exam->results()->create($validated);

        return redirect(route('subjects.exams.show', [$subject, $exam]))->with('create', "Results added successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
