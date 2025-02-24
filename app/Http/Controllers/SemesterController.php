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

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Semester/List', [
            'years' => AcademicYear::all(),
            'semesters' => Semester::with('year')->orderByDesc('start_date')->get(),
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
        return Inertia::render('Semester/Show', [
            'semester' => $semester,
            'grades' => Grade::whereHas('exams', function($query) use ($semester){
                $query->where('semester_id', '=', $semester->id);
            })->get(),
        ]);
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
        $results = Result::whereHas('exam', function($query) use($semester, $grade){
            $query
                ->where('semester_id', '=', $semester->id)
                ->where('grade_id', '=', $grade->id);
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
            'semester' => $semester,
            'grade' => $grade
        ]);

    }
}
