<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Grade;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use PDF;

class PDFController extends Controller
{
    public function gradeSemesterResults(Semester $semester, Grade $grade){
        $results = DB::table('subject_averages')
                ->join('grades as g', 'subject_averages.grade_id', '=', 'g.id')
                ->select(
                    'subject_averages.student_id',
                    'subject_averages.studentId',
                    'subject_averages.student_name',
                    DB::raw('SUM(subject_averages.subject_avg) as total_marks'),
                    DB::raw('RANK() OVER (PARTITION BY g.id ORDER BY SUM(subject_averages.subject_avg) DESC) as rank')
                )
                ->groupBy('subject_averages.student_id', 'subject_averages.studentId', 'subject_averages.student_name', 'g.id')
                ->orderByDesc('total_marks')
                ->fromSub(function ($query) use ($semester, $grade) {
                    $query->from('students as s')
                        ->join('results as r', 's.id', '=', 'r.student_id')
                        ->join('exams as e', 'r.exam_id', '=', 'e.id')
                        ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
                        ->select(
                            's.id as student_id',
                            's.studentId as studentId',
                            's.name as student_name',
                            'e.grade_id as grade_id',
                            DB::raw('ROUND(AVG(r.result)) as subject_avg')
                        )
                        ->where('e.semester_id', $semester->id)
                        ->where('e.grade_id', $grade->id)
                        ->groupBy('s.id', 's.studentId', 's.name', 'sub.id', 'e.grade_id');
                }, 'subject_averages')
                ->get();


        $pdf = Pdf::loadView('pdf.template', [
            'results' => $results,
            'semester' => $semester,
            'year' => [],
            'grade' => $grade
        ]);

        return $pdf->download("$semester->title $grade->name Results");
    }

    public function gradeYearResults(AcademicYear $academicYear, Grade $grade){
        $results = DB::table('subject_averages')
                ->join('grades as g', 'subject_averages.grade_id', '=', 'g.id')
                ->select(
                    'subject_averages.student_id',
                    'subject_averages.studentId',
                    'subject_averages.student_name',
                    DB::raw('SUM(subject_averages.subject_avg) as total_marks'),
                    DB::raw('RANK() OVER (PARTITION BY g.id ORDER BY SUM(subject_averages.subject_avg) DESC) as rank')
                )
                ->groupBy('subject_averages.student_id', 'subject_averages.studentId', 'subject_averages.student_name', 'g.id')
                ->orderByDesc('total_marks')
                ->fromSub(function ($query) use ($academicYear, $grade) {
                    $query->from('students as s')
                        ->join('results as r', 's.id', '=', 'r.student_id')
                        ->join('exams as e', 'r.exam_id', '=', 'e.id')
                        ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
                        ->join('semesters as sem', 'e.semester_id', '=', 'sem.id')
                        ->join('academic_years as year', 'sem.academic_year_id', '=', 'year.id')
                        ->select(
                            's.id as student_id',
                            's.studentId as studentId',
                            's.name as student_name',
                            'e.grade_id as grade_id',
                            DB::raw('ROUND(AVG(r.result)) as subject_avg')
                        )
                        ->where('year.id', $academicYear->id)
                        ->where('e.grade_id', $grade->id)
                        ->groupBy('s.id', 's.studentId', 's.name', 'sub.id', 'e.grade_id');
                }, 'subject_averages')
                ->get();


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
        $results = DB::table('students as s')
            ->join('results as r', 's.id', '=', 'r.student_id')
            ->join('exams as e', 'r.exam_id', '=', 'e.id')
            ->join('subjects as sub', 'e.subject_id', '=', 'sub.id')
            ->select(
                's.id as id',
                's.studentId as student_id',
                's.name as student_name',
                'sub.title as subject_name',
                'e.semester_id',
                's.grade_id',
                DB::raw('SUM(r.result) as total_marks'),
                DB::raw('ROUND(AVG(r.result)) as average_marks'),
                DB::raw('RANK() OVER (PARTITION BY sub.id ORDER BY SUM(r.result) DESC) as rank')
            )
            ->where('e.semester_id', $semester->id)
            ->where('e.grade_id', $grade)
            ->groupBy('s.id', 's.name', 'sub.title', 'sub.id', 'e.semester_id', 's.grade_id')
            ->orderBy('total_marks')
            ->get();

        $totalResults = $results->where('id', $student->id)->sum('average_marks');
        $position = 1;
        $res = [];
        $subjects = [];

        foreach ($results as $key) {
            if($key->id != $student->id && !array_key_exists($key->id, $res)){
                $studentResults = round($results->where('id', $key->id)->sum('average_marks'));
                $res[$key->id] = $studentResults;
                if($studentResults > $totalResults){
                    $position += 1;
                }
            }
        }


        foreach ($results->where('id', $student->id) as $key) {
            $subjects[] = [
                'subject_name' => $key->subject_name,
                'average_marks' => $key->average_marks,
                'grade' => $this->getGrade($key->average_marks)
            ];
        }

        return [
            'id' => $student->id,
            'studentId' => $student->studentId,
            'name' => $student->name,
            'total_marks' => $totalResults,
            'subjects' => $subjects,
            'position' => $position
        ];

    }

    public function studentResults(Student $student, Semester $semester){
        $results = $this->getSemResults($student, $semester);

        $pdf = Pdf::loadView('pdf.student', [
                'id' => $student->id,
                'studentId' => $student->studentId,
                'name' => $student->name,
                'total_marks' => $totalResults,
                'subjects' => $subjects,
                'position' => $position
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
