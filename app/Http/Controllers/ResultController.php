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

class ResultController extends Controller
{
    private function getExamResults(){
        $results = Result::whereHas('student')
            ->with([
                'student:id,name,studentId',
                'exam:id,title,subject_id,semester_id,grade_id',
                'exam.subject:id,title',
                'exam.semester:id,title,academic_year_id'
            ])
            ->get()
            ->map(function ($result) {
                return [
                    'exam'      => $result->exam->title,
                    'subject'   => $result->exam->subject->title,
                    'grade'   => $result->exam->grade->name,
                    'year'   => $result->exam->semester->year->year,
                    'semester'   => $result->exam->semester->title,
                    'student'   => $result->student->studentId,
                    'name'   => $result->student->name,
                    'result'    => $result->result
                ];
            })
            ->groupBy(['student', 'year', 'grade','semester', 'exam'])
            ->map(function ($studentExams) {
                return [
                    'id'   => $studentExams->first()->first()->first()->first()->first()['student'],
                    'name' => $studentExams->first()->first()->first()->first()->first()['name'],
                    'years' => $studentExams->map(fn($gradeResults) =>
                    $gradeResults->reduce(function ($carry, $semResults, $year) use($studentExams) {
                        $semCalculatedResults = $semResults->map(function($sem, $title){
                            $examSummary = $sem->map(function($result, $key) {
                                $subjects = [];
                                $result->map(function($res) use (&$subjects) {
                                        if(count($subjects) == 8) return;
                                        $subjects[$res['subject']] = $res['result'];
                                    });
                                    return [
                                        'subjects' => $subjects,
                                        'total' => array_sum($subjects),
                                    ];
                                });

                            $semTotal = round($examSummary->avg('total'));
                            $totals = [];
                            collect($examSummary)->map(function ($exam) use(&$totals) {
                                collect($exam['subjects'])->map(function($val, $key) use(&$totals){
                                    array_key_exists($key, $totals) ? $totals[$key] += $val : $totals[$key] = $val;
                                });
                            });
                            $averages = collect($totals)->map(fn($t) => round($t/3));

                            return [
                                'total' => $semTotal,
                                'subject_averages' => $averages,
                                ...$examSummary
                            ];
                        });

                        $yearTotal = $semCalculatedResults->avg('total');
                        $totals = [];
                        collect($semCalculatedResults)->map(function ($exam) use(&$totals) {
                            collect($exam['subject_averages'])->map(function($val, $key) use(&$totals){
                                array_key_exists($key, $totals) ? $totals[$key] += $val : $totals[$key] = $val;
                            });
                        });

                        $yearAverages = collect($totals)->map(fn($t) => round($t/2));

                        $carry[$year] = [
                            'total'    => $yearTotal,
                            'subject_averages' => $yearAverages,
                            ...$semCalculatedResults
                        ];

                        return $carry;
                    })),
                ];
            });

        return $results;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $examResults = $this->getExamResults();

            return Inertia::render('Result/Index', [
                'exam_results' => $examResults,
                'years' => AcademicYear::all(),
                'grades' => Grade::all(),
            ]);
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
        return Inertia::render('Result/Create', [
            'subject' => $subject,
            'exam' => Exam::with('subject', 'grade')->where('subject.id', '=', $subject->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResultRequest $request, Subject $subject, Exam $exam)
    {
        $validated = $request->validated();

        $result = $exam->results()->create($validated);

        return redirect(route('subjects.exams.show', [$subject, $exam]))->with('create', "Results added successfully!");
    }

    public function createMultiple(){
        return Inertia::render('Result/CreateMultiple', [
            'semesters' => Semester::with('year')->get(),
            'subjects' => Subject::all(),
            'students' => Student::all(),
            'grades' => Grade::all(),
            'exams' => Exam::all()
        ]);
    }

    public function storeMultiple(Request $request){
        $results = $request->input('results');
        $exam = Exam::find($results[0]['exam_id']);

        DB::table('results')->insert($results);

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
         return Inertia::render('Result/Edit', [
            'subject' => $subject,
            'exam' => Exam::with('subject', 'grade')->where('subject.id', '=', $subject->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResultRequest $request,Subject $subject, Exam $exam,  Result $result)
    {
        $validated = $request->validated();

        $result->fill($validated);

        if($result->isDirty()){
            $result->save();
        }

        return back()->with('update', "Results updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, Exam $exam, Result $result)
    {
        $result->delete();

        return back()->with('delete', "Results deleted successfully!");
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, Subject $subject, Exam $exam)
    {
        $result = Result::onlyTrashed()->where('id', $request->input('id'))->first();
        $result->restore();

        return redirect(route('subjects.exams.show', [$subject, $exam]))->with('update', 'Result restored!');
    }
}
