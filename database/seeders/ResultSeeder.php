<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = DB::table('students')->select('id', 'grade_id')->get()->toArray();
        $exams = DB::table('exams')->select('id', 'grade_id', 'exam_date')->get()->toArray();
        shuffle($exams);
        $grades = DB::table('grades')->select('id')->get()->toArray();
        $results = [];

        function getGrade($num){
            if ($num > 85) {
                return 'A';
            }else if ($num > 70) {
                return 'B';
            }else if ($num > 55) {
                return 'C';
            }else if ($num > 40) {
                return 'D';
            } else {
                return 'E';
            }

        }

        $n = 0;
        foreach ($students as $student) {
            foreach ($exams as $exam) {
                $exam_date = date_create($exam->exam_date);
                $today = date_create(now());
                $diff = date_diff($today, $exam_date);
                $diff = explode(' ', $diff->format('%R %y'));
                $grade = ($diff[0] == '-' ? $student->grade_id - ((int)$diff[1] + 1) : $student->grade_id + (int)$diff[1]);
                if($grade === $exam->grade_id){
                    $score = rand(30, 100);
                    $results[] = [
                        'result' => $score,
                        'grade' => getGrade($score),
                        'student_id' => $student->id,
                            'exam_id' => $exam->id,
                            'created_at' => now(),
                            'updated_at' => now()
                    ];
                    $n += 1;
                }
            }
        }

        DB::table('results')->insert($results);
    }
}
