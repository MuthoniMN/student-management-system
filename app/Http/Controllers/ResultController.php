<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\ResultRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\ResultService;

class ResultController extends Controller
{
    public function __construct(
        protected ResultService $resultService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $dependencies = $this->resultService->index();

            return Inertia::render('Result/Index', $dependencies);
        } catch(\Throwable $th){
            print_r($th->getMessage());
            print_r($th->getTraceAsString());
            die();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Subject $subject, Exam $exam)
    {
        $dependencies = $this->resultService->create($exam);

        return Inertia::render('Result/Create', $dependencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResultRequest $request, Subject $subject, Exam $exam)
    {
        $validated = $request->validated();

        $result = $this->resultService->create($exam, $validated);

        return redirect(route('subjects.exams.show', [$subject, $exam]))->with('create', "Results added successfully!");
    }

    public function createMultiple(){
        $dependencies = $this->resultService->createMany();

        return Inertia::render('Result/CreateMultiple', $dependencies);
    }

    public function storeMultiple(Request $request){
        $results = $request->input('results');
        $exam = Exam::find($results[0]['exam_id']);

        $this->resultService->create($results);

        return redirect(route('subjects.exams.show', [$exam->subject->id, $exam->id]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject, Exam $exam, Result $result)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject, Exam $exam, Result $result)
    {
        $dependencies = $this->resultService->edit($result);

         return Inertia::render('Result/Edit', $dependencies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResultRequest $request,Subject $subject, Exam $exam,  Result $result)
    {
        $validated = $request->validated();

        $result = $this->resultService->update($result, $validated);

        return back()->with('update', "Results updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Exam $exam, Result $result)
    {
        $this->resultRepository->delete($result);

        return back()->with('delete', "Results deleted successfully!");
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, Subject $subject, Exam $exam)
    {
        $result = $this->resultRepository->restore($request->input('id'));

        return redirect(route('subjects.exams.show', [$subject, $exam]))->with('update', 'Result restored!');
    }
}
