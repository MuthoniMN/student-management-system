<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\YearRequest;
use App\Http\Requests\YearUpdateRequest;
use Illuminate\Support\Facades\DB;
use App\Services\YearService;

class YearController extends Controller
{
    public function __construct(
        protected YearService $yearService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dependencies = $this->yearService->index();
        return Inertia::render('Year/List', $dependencies);
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

        $year = $this->yearService->create($validated);

        return redirect(route('years.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        $dependencies = $this->yearService->view($academicYear);
        return Inertia::render('Year/Show', $dependencies);
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

        $year = $this->yearService->update($academicYear, $validated);

        return back()->with('update', 'Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $this->yearService->delete($academicYear);

        return back()->with('delete', "Deleted!");
    }

    public function yearResults(AcademicYear $academicYear, Grade $grade){
        $dependencies = $this->yearService->yearResults($academicYear, $grade);

        return Inertia::render('Grade/Result', $dependencies);

    }

}
