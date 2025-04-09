<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $parents = DB::table('parents')->pluck('id')->toArray(); // Get parent IDs

        $students = [];
        for ($i = 1; $i <= 8; $i++) {
            $s = 1;
            while($s <= 10){
                $students[] = [
                    'studentId' => strtoupper(Str::random(8)), // Unique student ID
                    'grade_id' => $i, // Random grade from 1 to 8
                    'name' => fake()->name(), // Random readable student name
                    'parent_id' => $parents[array_rand($parents)], // Assign a random parent
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $s += 1;
            }
        }

        DB::table('students')->insert($students);
        $savedStudents = Student::all();

        $studentUsers = collect($savedStudents)->map(function ($stu) {
            return [
                'name' => $stu->name,
                'studentId' => $stu->studentId,
                'created_at' => $stu->created_at,
                'password' => Hash::make($stu->studentId),
                'updated_at' => $stu->updated_at,
                'student_id' => $stu->id,
                'role' => 'student'
            ];
        });

        DB::table('users')->insert($studentUsers->toArray());
    }
}

