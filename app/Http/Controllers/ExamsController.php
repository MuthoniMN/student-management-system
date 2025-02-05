<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExamRequest;
use App\Http\Requests\ExamUpdateRequest;

class ExamsController extends Controller
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
    public function create(Subject $subject)
    {
        return Inertia::render('Exam/Create',[
           'subject' => $subject,
           'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')->select('semesters.*', 'academic_years.year as year')->get(),
            'grades' => Grade::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request, Subject $subject)
    {
        $validated = $request->validated();

        $exam = $subject->exams()->create($validated);

        if($request->file('file')){
            $path = $request->file('file')->storeAs("exams", "{$exam->title} - {$exam->semester->title} {$exam->semester->year->year}.{$request->file('file')->getClientOriginalExtension()}", 'public');
            $url = Storage::url($path);

            $exam->file = $url;
            $exam->save();
        }

        return redirect(route('subjects.show', $subject))->with('create', 'Exam successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject, Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Exam $exam)
    {
        return Inertia::render('Exam/Edit',[
            'exam' => $exam,
            'subject' => $subject,
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', '=', 'academic_years.id')->select('semesters.*', 'academic_years.year as year')->get(),
            'grades' => Grade::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamUpdateRequest $request, Subject $subject, Exam $exam)
    {
        $validated = $request->validated();

        $exam->fill($validated);

        if($request->file('file')){
            $path = $request->file('file')->storeAs("exams", "{$exam->title} - {$exam->semester->title} {$exam->semester->start_date}.{$request->file('file')->getClientOriginalExtension()}", 'public');
            $url = Storage::url($path);

            $exam->file = $url;
        }

        if($exam->isDirty()){
            $exam->save();
        }

        return redirect(route('subjects.show', $subject))->with('update', 'Exam successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Exam $exam)
    {
        //
    }
}
