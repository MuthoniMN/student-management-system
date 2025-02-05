<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = DB::table('subjects')->select('id')->get()->toArray();
        $grades = DB::table('grades')->select('id')->get()->toArray();
        $semesters = DB::table('semesters')->select('id')->get()->toArray();
        $exams = [];

        for($i = 0; $i < 10; $i++){
            $n = $i + 4;
            $gap = "{$n} days";
            $exams[] = [
                'title' => "Final assessment",
                'grade_id' => $grades[array_rand($grades)]->id,
                'subject_id' => $subjects[array_rand($subjects)]->id,
                'semester_id' => $semesters[array_rand($semesters)]->id,
                'exam_date' => date('Y-m-d h:i:sa',strtotime($gap, strtotime(now()))),
                'file' => null,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('exams')->insert($exams);
    }
}
