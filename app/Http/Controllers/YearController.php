<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Result;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\YearRequest;
use App\Http\Requests\YearUpdateRequest;
use Illuminate\Support\Facades\DB;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Year/List', [
            'years' => AcademicYear::select('*')->orderByDesc('start_date')->get(),
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
        return Inertia::render('Year/Show', [
            'year' => $academicYear,
            'grades' => Grade::whereHas('exams.semester', function($query) use ($academicYear){
                $query->where('academic_year_id', '=', $academicYear->id);
            })->get(),
        ]);
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

    public function yearResults(AcademicYear $academicYear, Grade $grade){
        $results = Result::whereHas('exam', function($query) use($grade){
            $query
                ->where('grade_id', '=', $grade->id);
        })->whereHas('exam.semester', function($query) use($academicYear){
            $query
                ->where('academic_year_id', '=', $academicYear->id);
        })->with('student', 'exam', 'exam.subject')->get()
            ->map(function($result) {
                $result->subject = $result->exam->subject->title;
                $result->name = $result->student->name;
                $result->studentId = $result->student->studentId;

                return $result;
            })
          ->groupBy('student_id', 'results.id')
          ->map(function($result) {
              return $result
                  ->groupBy(fn($q) => $q->subject)
                  ->map(function($subj, $key) {
                      return [
                      'id' => $subj->first()->studentId,
                      'name' => $subj->first()->name,
                      'average' => round($subj->avg('result')),
                      'subject' => $key
                  ];});
          })->map(function($res) {
                return [
                  'id' => $res->first()['id'],
                  'name' => $res->first()['name'],
                  'subject' => $res->reduce(function($c, $val){
                      $c[$val['subject']] = $val['average'];
                      return $c;
                  }, []),
                  'total' => round($res->sum('average'))
              ];
          })->toArray();

        return Inertia::render('Grade/Result', [
            'results' => $results,
            'year' => $academicYear,
            'grade' => $grade
        ]);

    }

}
