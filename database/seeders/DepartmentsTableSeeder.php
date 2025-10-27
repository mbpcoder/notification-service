<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        if (!DB::table('departments')->where('id', 1)->exists()) {
            DB::table('departments')->insert([
                'id' => 1,
                'name' => 'Software Engineering',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
