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
use App\Services\SemesterService;

class SemesterController extends Controller
{
    public function __construct(
        protected SemesterService $semesterService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependencies = $this->semesterService->index();

        return Inertia::render('Semester/List', $dependencies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dependencies = $this->semesterService->create();

        return Inertia::render('Semester/Create', $dependencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SemesterRequest $request)
    {
        $validated = $request->validated();

        $semester = $this->semesterService->store($validated);

        return redirect(route('semesters.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        $dependencies = $this->semesterService->show($semester);

        return Inertia::render('Semester/Show', $dependencies);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        $dependencies = $this->semesterService->edit($semester);

        return Inertia::render('Semester/Edit', $dependencies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, Semester $semester)
    {
        $validated = $request->validated();

        $semester = $this->semesterService->update($semester, $validated);

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
        $dependencies = $this->semesterService->semesterResults($semester, $grade);

        return Inertia::render('Grade/Result', $dependencies);

    }
}
