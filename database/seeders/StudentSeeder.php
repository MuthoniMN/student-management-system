<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $parents = DB::table('parents')->pluck('id')->toArray(); // Get parent IDs

        $students = [];
        for ($i = 1; $i <= 15; $i++) {
            $students[] = [
                'studentId' => strtoupper(Str::random(8)), // Unique student ID
                'grade_id' => rand(1, 8), // Random grade from 1 to 8
                'name' => fake()->name(), // Random readable student name
                'parent_id' => $parents[array_rand($parents)], // Assign a random parent
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('students')->insert($students);
    }
}

