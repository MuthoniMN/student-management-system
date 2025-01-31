<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SemesterRequest;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Semester/List', [
            'years' => AcademicYear::all(),
            'semesters' => DB::table('semesters')->join('academic_years', 'semesters.academic_year_id', 'academic_years.id')->select('semesters.*', 'academic_years.year')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Semester/Create', [
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SemesterRequest $request)
    {
        $validated = $request->validated();

        $semester = Semester::create($validated);

        return redirect(route('semesters.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        return Inertia::render('Semester/Edit', [
            'semester' => $semester,
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, Semester $semester)
    {
        $validated = $request->validated();

        $semester->fill($validated);

        if($semester->isDirty()){
            $semester->save();
        }

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
}
