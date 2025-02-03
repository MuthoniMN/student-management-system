<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $years = [
            [
                'year' => '2023',
                'start_date' => date('Y-m-d h:i:sa', mktime(8,0,0,1,9,2023)),
                'end_date' => date('Y-m-d h:i:sa', mktime(12, 30, 0, 12, 1, 2023)),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'year' => '2024',
                'start_date' => date('Y-m-d h:i:sa', mktime(8, 0, 0, 1, 8, 2024)),
                'end_date' => date('Y-m-d h:i:sa', mktime(12, 30, 0, 11, 29, 2024)),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'year' => '2025',
                'start_date' => date('Y-m-d h:i:sa', mktime(8, 0, 0, 1, 6, 2025)),
                'end_date' => date('Y-m-d h:i:sa', mktime(12, 30, 0, 11, 28,2025)),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('academic_years')->insert($years);
    }
}
