<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBolumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('user_bolum')->truncate();
        DB::table('user_bolum')->insert([
            [
                'user_id' => 1,
                'bolum_id' => 1,
            ],
            [
                'user_id' => 2,
                'bolum_id' => 1,
            ],
            [
                'user_id' => 3,
                'bolum_id' => 2,
            ],
            [
                'user_id' => 4,
                'bolum_id' => 3,
            ],
            [
                'user_id' => 6,
                'bolum_id' => 1,
            ],
            [
                'user_id' => 7,
                'bolum_id' => 1,
            ],
        ]);
    }
}
