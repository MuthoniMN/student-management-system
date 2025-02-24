<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use PDF;

class PDFController extends Controller
{
    public function gradeSemesterResults(Semester $semester, Grade $grade){
        $results = array_values(Result::whereHas('exam', function($query) use($semester, $grade){
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
          })->sortByDesc('total')->toArray());


        $pdf = Pdf::loadView('pdf.template', [
            'results' => $results,
            'semester' => $semester,
            'year' => [],
            'grade' => $grade
        ]);

        return $pdf->download("$semester->title $grade->name Results");
    }

    public function gradeYearResults(AcademicYear $academicYear, Grade $grade){

        $results = Result::whereHas('exam', function ($query) use ($grade, $academicYear) {
                $query->where('grade_id', '=', $grade->id)
                      ->whereHas('semester', function ($q) use ($academicYear) {
                          $q->where('academic_year_id', '=', $academicYear->id);
                      });
            })
            ->with(['student:id,name,studentId', 'exam:id,title,subject_id,semester_id', 'exam.subject:id,title', 'exam.semester:id,title'])
            ->get()
            ->map(function ($result) {
                return [
                    'studentId' => $result->student->studentId,
                    'name'      => $result->student->name,
                    'exam'      => $result->exam->title,
                    'subject'   => $result->exam->subject->title,
                    'semester'   => $result->exam->semester->title,
                    'result'    => $result->result
                ];
            })
            ->groupBy(['studentId', 'semester', 'exam'])
            ->map(function ($studentExams) {
                return $studentExams->map(function ($examResults, $examTitle) {
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
                        'id'       => $examResults->first()->first()['studentId'],
                        'name'     => $examResults->first()->first()['name'],
                        'total'    => array_sum($subjects),
                        'subjects' => $subjects,
                        'exam'     => $examTitle,
                    ];
                });
            })
            ->map(function ($studentResults) use (&$subjectTotals) {
                $totals = [];
                $subjectAverages = $studentResults->map(function($results) use(&$totals){ collect($results['subjects'])->map(function($val, $key) use(&$totals){
                    array_key_exists($key, $totals) ? $totals[$key] += $val : $totals[$key] = $val;
                });
                });
                $averages = collect($totals)->map(fn($t) => round($t/2));

                return [
                    'id'              => $studentResults->first()['id'],
                    'name'            => $studentResults->first()['name'],
                    'exams'            => $studentResults,
                    'subject_averages'=> $averages,
                    'total'           => round($studentResults->avg('total'))
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        $pdf = Pdf::loadView('pdf.template', [
            'results' => $results,
            'year' => $academicYear,
            'semester' => [],
            'grade' => $grade
        ]);

        return $pdf->download("$academicYear->year $grade->name Results");
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

    private function getSemResults(Student $student, Semester $semester){
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

        $res = [];
        $subjects = [];


        return [
            'id' => $student->id,
            'studentId' => $student->studentId,
            'name' => $student->name,
            'results' => $results,
        ];

    }

    public function studentResults(Student $student, Semester $semester){
        $results = $this->getSemResults($student, $semester);

        $pdf = Pdf::loadView('pdf.student', [
                'results' => $results,
            ]);

        return $pdf->download("$student->name Results for $semester->title");
    }

    public function studentYearlyResults(Student $student, AcademicYear $academicYear){
        $semesters = $academicYear->semesters()->get();
        $results = [];
        $compiled = [];
        $ranks=[];
        $totals=[];

        foreach ($semesters as $semester) {
            $results[$semester->title] = $this->getSemResults($student, $semester);
        }

        foreach ($results as $key => $sem) {
            $total = 0;
            foreach ($sem['subjects'] as $result) {
                $saved = array_key_exists($result['subject_name'], $compiled) ? $compiled[$result['subject_name']] : [];
                $compiled[$result['subject_name']] = [
                    ...$saved,
                    $key => $result['average_marks'],
                    "{$key}_grade" => $result['grade'],
                    'subject' => $result['subject_name']
                ];
                $total += $result['average_marks'];
                $totals[$key] = $total;
            }
            $ranks[$key] = $sem['position'];
        }

        foreach ($compiled as $key => $sub) {
            $avg = round(($sub['Semester 1'] + $sub['Semester 2']) / 2);
            $avg_grade = $this->getGrade($avg);
            $sub = [ ...$sub, 'average' => $avg, 'grade' => $avg_grade];
            $compiled[$key] = $sub;
        }

        $totals['average'] = round(($totals['Semester 1'] + $totals['Semester 2']) / 2);

        $pdf = Pdf::loadView('pdf.year', [
            'results' => $compiled,
            'ranks' => $ranks,
            'student' => $student,
            'year' => $academicYear,
            'totals' => $totals
        ]);

        return $pdf->download("$student->name Results for $academicYear->year");
    }
}
