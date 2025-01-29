<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 8; $i++) {
            $val = $i + 1;
            DB::table('grades')->insert([
                'name' => "Grade {$val}",
                'description' => "This is a class for Grade {$val} learners."
            ]);
        }
    }
}
