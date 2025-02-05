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
        $students = DB::table('students')->select('id')->get()->toArray();
        $exams = DB::table('exams')->select('id')->get()->toArray();
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

        foreach ($students as $student) {
            foreach ($exams as $exam) {
                $score = rand(1, 100);
                $results[] = [
                    'result' => $score,
                    'grade' => getGrade($score),
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        DB::table('results')->insert($results);
    }
}
