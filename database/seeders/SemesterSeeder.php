<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = AcademicYear::all();
        $semesters = [];

        foreach($years as $year){
            $semesters[] = [
                'title'=> 'Semester 1',
                'start_date'=> $year->start_date,
                'end_date'=> date('Y-m-d h:i:sa',strtotime("+13 weeks", strtotime($year->start_date))),
                'academic_year_id' => $year->id,
                'created_at'=> now(),
                'updated_at'=> now()
            ];

            $semesters[] = [
                'title'=> 'Semester 2',
                'start_date'=> date('Y-m-d h:i:sa',strtotime("-11 weeks", strtotime($year->end_date))),
                'end_date'=> $year->end_date,
                'academic_year_id' => $year->id,
                'created_at'=> now(),
                'updated_at'=> now()
            ];
        }

        DB::table('semesters')->insert($semesters);
    }
}
