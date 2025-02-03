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

        foreach($titles as $title){
            $subjects[] = [
                'title' => $title,
                'description' => $title . ' for Learners.',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('subjects')->insert($subjects);

        $subjects = DB::table('subjects')->get();
    }
}
