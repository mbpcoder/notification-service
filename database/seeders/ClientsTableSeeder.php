<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        if (!DB::table('clients')->where('id', 1)->exists()) {
            DB::table('clients')->insert([
                'id' => 1,
                'department_id' => 1,
                'name' => 'Panel Client',
                'token' => 'asdahbsvdhbashjBHasdasdasdjnskadnas565',
                'is_active' => true,
                'expired_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
