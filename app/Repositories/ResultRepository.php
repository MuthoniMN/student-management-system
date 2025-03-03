<?php

namespace App\Repositories;

use App\Interfaces\ResultRepositoryInterface;
use App\Models\Result;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\Exam;
use Illuminate\Support\Collection;

class ResultRepository implements ResultRepositoryInterface
{
    /**
     * @desc getting the grade
     * @param int $num
     * @return string
     * */
    private function getGrade($num): string {
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

    /**
     * @desc get all results
     * @return Collection
     * */
    public function findAll(): Collection
    {
        return Result::with([
            'student:id,studentId,name',
            'exam:id,title,subject_id,semester_id,grade_id',
            'exam.subject:id,title',
            'exam.semester:id,title,academic_year_id',
            'exam.semester.year:id,year',
            'exam.grade:id,title'
        ])->get();
    }

    /**
     * @desc get a result using it's id
     * @param int $id
     * @return Result
     * */
    public function findById(int $id): Result
    {
        return Result::with([
            'student:id,studentId,name',
            'exam:id,title,subject_id,semester_id,grade_id',
            'exam.subject:id,title',
            'exam.semester:id,title,academic_year_id',
            'exam.semester.year:id,year',
            'exam.grade:id,title'
        ])->where('id', '=', $id)->first();
    }

    /**
     * @desc get a result
     * @param Result $result
     * @return Result
     * */
    public function get(Result $result): Result
    {
        return Result::with([
            'student:id,studentId,name',
            'exam:id,title,subject_id,semester_id,grade_id',
            'exam.subject:id,title',
            'exam.semester:id,title,academic_year_id',
            'exam.semester.year:id,year',
            'exam.grade:id,name'
        ])->where('id', '=', $result->id)->first();
    }

    /**
     * @desc create a result
     * @param array $attributes
     * @return Result
     * */
    public function create(array $attributes): Result
    {
        $result = Result::create($attributes);

        return $result;
    }

    /**
     * @desc create multiple result instances
     * @param array $results
     * @return Collection
     * */
    public function createMany(array $results): Collection
    {
        return DB::table('results')->insert($results);
    }


    /**
     * @desc get exam results
     * @param $exam
     * @return Collection
     * */
    public function findExamResults(Exam $exam): Collection
    {
        return Result::with(['exam', 'student', 'exam.grade', 'exam.subject', 'exam.semester', 'exam.semester.year'])->where('exam_id', '=', $exam->id)->get();
    }

    /**
     * @desc utility function to get student grade in a particular year
     * @param Student $student
     * @param string $date
     * @return int*/
    private function getStudentGrade(Student $student, string $date): int
    {
        $date = date_create($date);
        $today = date_create(now());
        $diff = date_diff($today, $date);
        $diff = explode(' ', $diff->format('%R %y'));
        $grade = ($diff[0] == '-' ? $student->grade_id - ((int)$today->format('y') - (int)$date->format('y')) : $student->grade_id + (int)$diff[1]);

        return $grade;
    }

    /**
     * @desc get student's results in a specified semester
     * @param Student $student
     * @param Semester $semester
     * @return array
     * */
    public function getStudentSemesterResults(Student $student, Semester $semester) : array
    {
        $grade = $this->getStudentGrade($student, $semester->start_date);

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

        return $results;
    }

    /***
     * @desc returns cumulative results
     * @return Collection
     * */
    public function getResults(){
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
     * @desc get a student grade's ranks in the specified semester
     * @param Student $student
     * @param Semester $semester
     * @return array
     * */
    public function getStudentSemesterRanks(Student $student, Semester $semester): Collection
    {
        $grade = $this->getStudentGrade($student, $semester->start_date);

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

        return $ranks;

    }

    /**
     * @desc get the student's perfomance throughout an academic year
     * @param Student $student
     * @param AcademicYear $academicYear
     * @return array
     * */
    public function getStudentYearResults(Student $student, AcademicYear $academicYear): Collection
    {
        $grade = $this->getStudentGrade($student, $academicYear->start_date);

        $results = Result::whereHas('exam', function ($query) use ($grade, $academicYear) {
                $query->where('grade_id', '=', $grade)
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

        return $results;
    }

    /**
     * @desc get the student's grade's rank for the semester
     * @param Student $student
     * @param AcademicYear $academicYear
     * @return array
     * */
    public function getStudentYearRanks(Student $student, AcademicYear $academicYear): Collection
    {
        $grade = $this->getStudentGrade($student, $academicYear->start_date);

        $ranks = Result::whereHas('exam', function ($query) use ($academicYear, $grade, $student) {
                $query->where('grade_id', '=', $grade)
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

        return $ranks;
    }

    /**
     * @desc get semester results for students in the specified grade
     * @param Grade $grade
     * @param Semester $semester
     * @return array
     * */
    public function getGradeSemesterResults(Grade $grade, Semester $semester): array
    {
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

        return $results;
    }

    /**
     * @desc get the ranks for student's in the specified grade for the semester
     * @param Grade $grade
     * @param Semester $semester
     * @return array
     * */
    public function getGradeSemesterRanks(Grade $grade, Semester $semester){
        $ranks = Result::whereHas('exam', function($query) use($semester, $grade){
            $query
                ->where('semester_id', '=', $semester->id)
                ->where('grade_id', '=', $grade->id);
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

        return $ranks;
    }

    /**
     * @desc get the grade's students in an academic year
     * @param Grade $grade
     * @param AcademicYear $academicYear
     * @return array
     * */
    public function getGradeYearResults(Grade $grade, AcademicYear $academicYear): array
    {
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

        return $results;
    }

    public function getGradeYearRanks(Grade $grade, AcademicYear $academicYear){
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

        return $ranks;

    }

    /**
     * @desc update a result
     * @param Result $result
     * @param array $attributes
     * */
    public function update(Result $result, array $attributes){
        $result->fill($attributes);

        if($result->isDirty()){
            $result->save();
        }

    }

    /**
     * @desc delete a result
     * @param Result $result
     * @return Result
     * */
    public function delete(Result $result): Result
    {
        return $result->delete();
    }

    /**
     * @desc restore a soft deleted result
     * @param int $id
     * @return Result
     * */

    public function restore(int $id): Result
    {
        $result = Result::onlyTrashed()->where('id', $id)->first();
        $result->restore();

        return $result;
    }

}
