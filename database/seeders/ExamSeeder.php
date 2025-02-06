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
        $semesters = DB::table('semesters')->select('id', 'start_date')->get()->toArray();
        $exams = [];

        foreach ($subjects as $subject){
            foreach ($grades as $grade) {
                foreach ($semesters as $semester) {
                    $n = rand(14, 70);
                    $gap = "{$n} days";
                    $exams[] = [
                        'title' => "Final assessment",
                        'type' => "exam",
                        'grade_id' => $grade->id,
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                        'exam_date' => date('Y-m-d h:i:sa',strtotime($gap, strtotime($semester->start_date))),
                        'file' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $exams[] = [
                        'title' => "CAT 1",
                        'type' => "CAT",
                        'grade_id' => $grade->id,
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                        'exam_date' => date('Y-m-d h:i:sa',strtotime($gap, strtotime($semester->start_date))),
                        'file' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $exams[] = [
                        'title' => "CAT 2",
                        'type' => "CAT",
                        'grade_id' => $grade->id,
                        'subject_id' => $subject->id,
                        'semester_id' => $semester->id,
                        'exam_date' => date('Y-m-d h:i:sa',strtotime($gap, strtotime($semester->start_date))),
                        'file' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

        }

        DB::table('exams')->insert($exams);
    }
}
