<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParentSeeder extends Seeder
{
    public function run()
    {
        $parents = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '0700123456',
                'address' => '123 Elm Street, Springfield',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone_number' => '0712345678',
                'address' => '456 Maple Avenue, Rivertown',
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.johnson@example.com',
                'phone_number' => '0723456789',
                'address' => '789 Oak Lane, Lakeside',
            ],
            [
                'name' => 'Emily Brown',
                'email' => 'emily.brown@example.com',
                'phone_number' => '0734567890',
                'address' => '101 Pine Road, Mountainview',
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'michael.davis@example.com',
                'phone_number' => '0745678901',
                'address' => '202 Cedar Street, Baytown',
            ],
        ];

        DB::table('parents')->insert($parents);
    }
}

