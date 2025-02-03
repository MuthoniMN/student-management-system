<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Grade;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = ['Mathematics', 'English', 'Kiswahili', 'Science', 'History', 'CRE', 'Geography', 'Computer'];
        $grades = Grade::all();
        $subjects = [];

        foreach($grades as $grade){
            foreach($titles as $title){
                $subjects[] = [
                    'grade_id' => $grade->id,
                    'title' => $title,
                    'description' => $title . ' for ' . $grade->name . ' learners.',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        DB::table('subjects')->insert($subjects);

    }
}
