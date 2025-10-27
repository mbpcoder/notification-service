<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        if (!DB::table('users')->where('id', 1)->exists()) {
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@notification.local',
                'email_verified_at' => $now,
                'password' => bcrypt('123456'),
                'remember_token' => Str::random(100),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
