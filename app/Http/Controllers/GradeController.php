<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use Inertia\Inertia;
use App\Http\Requests\GradeRequest;
use App\Services\GradeService;

class GradeController extends Controller
{
    public function __construct(
        protected GradeService $gradeService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependencies = $this->gradeService->index();
        return Inertia::render('Grade/List', $dependencies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Grade/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradeRequest $request)
    {
        $validated = $request->validated();

        $grade = $this->gradeService->create($validated);

        return redirect(route('grades.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return Inertia::render('Grade/Edit', [
            'grade' => $grade,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradeRequest $request, Grade $grade)
    {
        $validated = $request->validated();

        $grade = $this->gradeService->edit($grade, $validated);

        return redirect(route('grades.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $this->gradeService->delete($grade);

        return redirect(route('grades.index'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $grade = $this->gradeService->restore($request->input('id'));

        return redirect(route('grades.index'))->with('update', 'Grade restored!');
    }
}
