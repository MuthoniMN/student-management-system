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
use App\Services\SubjectService;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectService $subjectService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependencies = $this->subjectService->index();
        return Inertia::render('Subject/Index', $dependencies);
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

        $subject = $this->subjectService->create($validated);

        return redirect(route('subjects.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $dependencies = $this->subjectService->show($subject);

        return Inertia::render('Subject/Show', $dependencies);
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
        $subject = $this->subjectService->update($subject, $validated);

        return redirect(route('subjects.index'))->with('update', 'Subject updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $this->subjectService->delete($subject);

        return redirect(route('subjects.index'))->with('delete', 'Subject deleted!');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $subject = $this->subjectService->restore($request->input('id'));

        return redirect(route('subjects.index'))->with('update', 'Subject restored!');
    }
}
