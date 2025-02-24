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
        $results = Result::whereHas('student')->with(['student:id,studentId,name', 'exam:id,title,subject_id,semester_id,grade_id', 'exam.subject:id,title', 'exam.semester:id,title,academic_year_id', 'exam.semester.year:id,year'])
            ->select('id', 'result', 'grade', 'exam_id', 'student_id')
            ->get()
            ->map(function($result) {
                $result->subject = $result->exam->subject->title;
                return $result;
            })
            ->groupBy([
            function($res){ return $res->student->studentId; },
            fn($res) => $res->exam->semester->year->year,
            fn($res) => $res->exam->grade->name,
            fn($res) => $res->exam->semester->title,
            fn($res) => $res->exam->title,
            ])->map(function ($res, $id) {
                $student = Student::where('studentId', '=', $id)->first();
                return [
                        'id' => $student->studentId,
                        'name' => $student->name,
                        'years' => $res->map(function($yearResults, $year) {
                            return $yearResults->map(function($gradeResults, $grade) {
                                    return  $gradeResults->map(function($semesterResults) {
                                        // Compute Totals Per Exam Title
                                        $examData = $semesterResults->map(function ($examResults) {
                                            // Calculate student totals per exam
                                            $examTotals = $examResults->sum('result');
                                            return [
                                                'total' => $examTotals,
                                                'results' => $examResults->pluck('result', 'subject'),
                                            ];
                                        });

                                    return $examData;
                                        });
                                });
                        }),
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
                'semesters' => Semester::with('year')->get(),
                'years' => AcademicYear::all(),
                'students' => Student::all(),
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
