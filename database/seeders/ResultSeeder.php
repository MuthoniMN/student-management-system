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

        for($i = 0; $i < 10; $i++){
            $results[] = [
                'result' => 80,
                'grade' => 'A',
                'exam_id' => $exams[array_rand($exams)]->id,
                'student_id' => $students[array_rand($students)]->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('results')->insert($results);
    }
}
