<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SubjectRequest;
use Illuminate\Support\Facades\Storage;

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
        //
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
}
