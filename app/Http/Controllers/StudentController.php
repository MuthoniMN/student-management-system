<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\UpgradeGradeRequest;
use App\Http\Requests\DeleteStudentsRequest;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependencies = $this->studentService->getAll();
        return Inertia::render('Student/List', $dependencies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dependencies = $this->studentService->create();
        return Inertia::render('Student/Create', $dependencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $validated = $request->validated();

        $student = $this->studentService->store($validated);

        return redirect(route('students.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $dependencies = $this->studentService->getStudent($student);

        return Inertia::render('Student/Show', $dependencies);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $dependencies = $this->studentService->edit();
        return Inertia::render('Student/Edit', $dependencies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        $validated = $request->validated();

        $student = $this->studentService->update($student, $validated);

        return redirect(route('students.show', $student));
    }

    /*
     * Update many student's grade
     * */
    public function upgrade(Request $request){
        $validated = $request->input('data');

        $this->studentService->upgrade($validated);

        return back()->with('update', 'Successfully updated!' );
    }

    /*
     * Mass delete students
     * */
    public function deleteMany(Request $request){
        $validated = $request->input('studentIds');

        $this->studentService->deleteMany($validated);

        return back()->with('delete', 'Successfully deleted!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->studentService->delete($student);

        return redirect(route('students.index'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $data = $request->input('data');

        $this->studentService->restoreMany($data['studentIds']);

        return redirect(route('students.index'))->with('update', 'Student restored!');
    }

    public function resultsAggregate(Student $student, Semester $semester){
        Gate::authorize('view', $student);

        $results = $this->studentService->getSemesterResults($student, $semester);

        return Inertia::render('Student/Results', [
            'results' => $results,
            'semester' => $semester
        ]);
    }

    public function yearlyResults(Student $student, AcademicYear $academicYear){
        Gate::authorize('view', $student);

        $dependencies = $this->studentService->getYearResults($student, $academicYear);

        return Inertia::render('Student/YearlyResults', $dependencies);
    }

    // dashboard
    public function dashboard(Request $request){
        $student = $request->user()->student;
        $dependencies = $this->studentService->getStudent($student);
        return Inertia::render('Student/Dashboard', $dependencies);
    }
}
