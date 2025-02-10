<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Semester;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SubjectRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Subject/Index', [
            'grades' => Grade::all(),
            'subjects' => Subject::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Subject/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubjectRequest $request)
    {
        $validated = $request->validated();

        $subject = Subject::create($validated);

        return redirect(route('subjects.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return Inertia::render('Subject/Show', [
            'subject' => $subject,
            'exams' => DB::table('exams')
                ->join('grades', 'exams.grade_id', '=', 'grades.id')
                ->join('semesters', 'exams.semester_id', '=', 'semesters.id')
                ->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')
                ->select('exams.*', 'grades.name as grade', 'semesters.title as semester', 'academic_years.year as year')
                ->where('exams.subject_id', $subject->id)
                ->where('exams.deleted_at', null)
                ->orderBy('exams.created_at', 'asc')
                ->get(),
            'grades' => Grade::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')->select('semesters.*', 'academic_years.year as year')->where('semesters.deleted_at', null)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return Inertia::render('Subject/Edit', [
            'subject' => $subject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubjectRequest $request, Subject $subject)
    {
        $validated = $request->validated();
        $subject->fill($validated);

        if($subject->isDirty()){
            $subject->save();
        }

        return redirect(route('subjects.index'))->with('update', 'Subject updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect(route('subjects.index'))->with('delete', 'Subject deleted!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $subject = Subject::withTrashed()->where('id', $request->input('id'))->first();
        $subject->restore();

        return redirect(route('subjects.index'))->with('update', 'Subject restored!');
    }
}
