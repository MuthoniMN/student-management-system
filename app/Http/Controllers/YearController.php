<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\YearRequest;
use App\Http\Requests\YearUpdateRequest;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Year/List', [
            'years' => AcademicYear::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Year/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(YearRequest $request)
    {
        $validated = $request->validated();

        $year = AcademicYear::create($validated);

        return redirect(route('years.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        return Inertia::render('Year/Edit', [
            'year' => $academicYear->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(YearUpdateRequest $request, AcademicYear $academicYear)
    {
        $validated = $request->validated();

        $academicYear->fill($validated);

        if($academicYear->isDirty()){
            $academicYear->save();
        }

        return back()->with('update', 'Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return back()->with('delete', "Deleted!");
    }
}
