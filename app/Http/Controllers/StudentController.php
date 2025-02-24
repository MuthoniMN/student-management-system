<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentData;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Result;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\UpgradeGradeRequest;
use App\Http\Requests\DeleteStudentsRequest;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Student/List', [
            "students" => Student::with(['grade', 'parent'])->get(),
            "grades" => Grade::all(),
            "parents" => ParentData::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Student/Create',[
            "parents" => ParentData::all()->makeHidden(['created_at', 'updated_at']),
            "grades" => Grade::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $validated = $request->validated();

        // find or create parent
        $parent = ParentData::firstOrCreate(
            [
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number']
            ],
            [
                'name' => $validated['parent_name'],
                'address' => $validated['address']
            ]
        );

        // find grade
        $grade = Grade::find((int)$validated['grade_id']);

        // create student
        $student = new Student;
        $student['studentId'] = $validated['studentId'];
        $student['name'] = $validated['name'];
        $student['parent_id'] = $parent->id;
        $student['grade_id'] = $grade->id;

        $student->save();

        return redirect(route('students.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return Inertia::render('Student/Show', [
            'student' => $student,
            'grade' => $student->grade,
            'parent' => $student->parent,
            'semesters' => Semester::whereHas('exams.results', function($q) use ($student) {
                $q->where('student_id', '=', $student->id);
            })->with('year')->get(),
            'years' => AcademicYear::whereHas('semesters.exams.results', function($q) use ($student) {
                $q->where('student_id', '=', $student->id);
            })->get(),
         ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return Inertia::render('Student/Edit', [
            'student' => $student,
            'parents' => ParentData::all(),
            'grades' => Grade::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        $validated = $request->validated();

        $parent = ParentData::find($student->parent->id);

        $parent->fill([
            'name' => $validated['parent_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address']
        ]);

        if($parent->isDirty()){
            $parent->save();
        }

        $student->name = $validated['name'];
        $student->studentId = $validated['studentId'];
        $student->grade_id = $validated['grade_id'];

        if($student->isDirty()){
            $student->save();
        }

        return redirect(route('students.show', $student));
    }

    /*
     * Update many student's grade
     * */
    public function upgrade(Request $request){
        $validated = $request->input('data');

        foreach ($validated['studentIds'] as $key) {
            $student = Student::find($key);

            $student['grade_id'] = $validated['grade_id'];

            $student->save();
        }

        return back()->with('update', 'Successfully updated!' );
    }

    /*
     * Mass delete students
     * */
    public function deleteMany(Request $request){
        $validated = $request->input('studentIds');

        Student::destroy($validated);

        return back()->with('delete', 'Successfully deleted!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect(route('students.index'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        $data = $request->input('data');
        $students = $data['studentIds'];
        foreach ($students as $id) {
            $student = Student::withTrashed()->where('id', $id)->first();
            $student->restore();
        }

        return redirect(route('students.index'))->with('update', 'Student restored!');
    }

    private function getGrade($num) {
        if($num > 80){
            return 'A';
        } else if($num > 65){
            return 'B';
        } else if($num > 50){
            return 'C';
        } else if($num > 40){
            return 'D';
        }else{
            return 'E';
        }
    }

    private function getSemesterResults(Student $student, Semester $semester){
        $date = date_create($semester->start_date);
        $today = date_create(now());
        $diff = date_diff($today, $date);
        $diff = explode(' ', $diff->format('%R %y'));
        $grade = ($diff[0] == '-' ? $student->grade_id - ((int)$today->format('y') - (int)$date->format('y')) : $student->grade_id + (int)$diff[1]);

        $results = Result::whereHas('exam', function($query) use($semester, $grade){
            $query
                ->where('semester_id', '=', $semester->id)
                ->where('grade_id', '=', $grade);
        })->where('student_id', '=', $student->id)
            ->with('student', 'exam', 'exam.subject')->get()
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
                  'subjects' => $res->reduce(function($c, $val){
                      $c[] = [
                          'subject_name' => $val['subject'],
                          'average_marks' => $val['average'],
                          'grade' => $this->getGrade($val['average']),
                      ];
                      return $c;
                  }, []),
                  'total' => round($res->sum('average'))
              ];
          })->toArray();

        $ranks = Result::whereHas('exam', function($query) use($semester, $grade){
            $query
                ->where('semester_id', '=', $semester->id)
                ->where('grade_id', '=', $grade);
        })->whereHas('student')->with('student', 'exam', 'exam.subject')->get()
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
                  'total' => round($res->sum('average')),
                  'id' => $res->first()['id'],
            ];
          })->sortByDesc('total')->values()
                                  ->map(fn($res, $index) => [ 'rank' => $index + 1, 'id' => $res['id'] ]);
        $rank = $ranks->search(fn($r, $key) => $r['id'] == $student->studentId);

        return [
            'id' => $student->id,
            'studentId' => $student->studentId,
            'name' => $student->name,
            'results' => [
                'subjects' => current($results)['subjects'],
                'total'=> current($results)['total'],
                'rank' => $ranks[$rank]['rank']
            ],
        ];

    }

    public function resultsAggregate(Student $student, Semester $semester){
        $results = $this->getSemesterResults($student, $semester);
        return Inertia::render('Student/Results', [
            'results' => $results,
            'semester' => $semester
        ]);
    }

    public function yearlyResults(Student $student, AcademicYear $academicYear){
        $semesters = $academicYear->semesters()->get();
        $results = [];
        $ranks=[];
        $grade= $student->grade;

        $ranks = Result::whereHas('exam', function ($query) use ($academicYear, $grade, $student) {
                $query->where('grade_id', '=', $grade->id)
                      ->whereHas('semester', function ($q) use ($academicYear) {
                          $q->where('academic_year_id', '=', $academicYear->id);
                      });
            })->whereHas('student')->with('student', 'exam', 'exam.subject', 'exam.semester')
              ->get()
              ->map(function ($result) use ($student) {
                  return [
                      'studentId' => $result->student->studentId,
                      'name' => $result->student->name,
                      'semester' => $result->exam->semester->title,
                      'subject' => $result->exam->subject->title,
                      'result' => $result->result,
                  ];
              })
              ->groupBy('semester')
              ->map(function ($semesterResults, $semester) use ($student) {
                  return $semesterResults->groupBy('studentId')->map(function ($studentResults, $studentId) {
                      return [
                          'studentId' => $studentId,
                          'name' => $studentResults->first()['name'],
                          'total' => round(($studentResults->sum('result'))/3),
                      ];
                  })->sortByDesc('total') // Sort students by total score per semester
                    ->values()
                    ->map(function ($res, $index) use ($student) {
                            return [
                                'rank' => $index + 1, // Assign rank based on position
                                'id' => $res['studentId'],
                                'total' => $res['total'],
                            ];
                    })->filter(fn($res) => $res['id'] === $student->studentId)->collapse();
              });

        $yearResults = Result::whereHas('exam', function ($query) use ($grade, $academicYear) {
                $query->where('grade_id', '=', $grade->id)
                      ->whereHas('semester', function ($q) use ($academicYear) {
                          $q->where('academic_year_id', '=', $academicYear->id);
                      });
            })->whereHas('student')
            ->with(['student:id,name,studentId', 'exam:id,title,subject_id,semester_id', 'exam.subject:id,title', 'exam.semester:id,title'])
            ->get()
            ->map(function ($result) {
                return [
                    'exam'      => $result->exam->title,
                    'subject'   => $result->exam->subject->title,
                    'semester'   => $result->exam->semester->title,
                    'student'   => $result->student->studentId,
                    'result'    => $result->result
                ];
            })
            ->groupBy(['student', 'semester', 'exam'])
            ->map(function ($studentExams) {
                return $studentExams->map(function ($examResults, $examTitle) use($studentExams) {
                    $subjects = [];
                    $examResults->groupBy('subject')->map(function($result) use (&$subjects) {
                        $result->map(function($res) use (&$subjects) {
                            $res->map(function($res) use (&$subjects){
                                if(count($subjects) == 8) return;
                                $subjects[$res['subject']] = $res['result'];
                            });
                        });
                    });

                    return [
                        'id'       => $studentExams->first()->first()->first()['student'],
                        'total'    => array_sum($subjects),
                        'subjects' => $subjects,
                        'exam'     => $examTitle,
                    ];
                });
            })
            ->map(function ($studentResults) use (&$subjectTotals) {
                $totals = [];
                $subjectAverages = collect($studentResults)->map(function($results) use(&$totals){
                    collect($results['subjects'])->map(function($val, $key) use(&$totals){
                    array_key_exists($key, $totals) ? $totals[$key] += $val : $totals[$key] = $val;
                });
                });
                $averages = collect($totals)->map(fn($t) => round($t/2));

                return [
                    'id'              => $studentResults->first()['id'],
                    'exams'            => $studentResults,
                    'subject_averages'=> $averages,
                    'total'           => round($studentResults->avg('total'))
                ];
            })->sortByDesc('total')
              ->values()
              ->map(fn($res, $index) => [ 'rank' => $index + 1, ...$res ])
              ->filter(fn($res) => $res['id'] === $student->studentId)->collapse();

        return Inertia::render('Student/YearlyResults', [
            'yearResults' => $yearResults,
            'ranks' => $ranks,
            'student' => $student,
            'year' => $academicYear
        ]);
    }


}
