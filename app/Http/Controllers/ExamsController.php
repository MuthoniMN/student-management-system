<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExamRequest;
use App\Http\Requests\ExamUpdateRequest;
use App\Services\ExamService;

class ExamsController extends Controller
{
    public function __construct(
        protected ExamService $examService
    ) {}
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
        $dependencies = $this->examService->create($subject);
        return Inertia::render('Exam/Create', $dependencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request, Subject $subject)
    {
        $validated = $request->validated();

        $exam = $this->examService->store($subject, $validated, $request->file('file'));

        return redirect(route('subjects.show', $subject))->with('create', 'Exam successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject, Exam $exam)
    {
        $dependencies = $this->examService->show($subject, $exam);

        return Inertia::render('Exam/Show', $dependencies);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Exam $exam)
    {
        $dependencies = $this->examService->edit($subject, $exam);
        return Inertia::render('Exam/Edit', $dependencies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamUpdateRequest $request, Subject $subject, Exam $exam)
    {
        $validated = $request->validated();

        $exam = $this->examService->update($subject, $validated, $request->file('file'));

        return redirect(route('subjects.show', $subject))->with('update', 'Exam successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Exam $exam)
    {
        $this->examService->delete();

        return redirect(route('subjects.show', $subject))->with('delete', 'Exam successfully deleted!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, Subject $subject)
    {
        $exam = $this->examService->restore($request->input('id'));

        return redirect(route('subjects.show', $exam->subject))->with('update', 'Exam restored!');
    }
}
