<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('user_ders')->truncate();
        DB::table('user_ders')->insert([
            [
                'user_id' => 1,
                'ders_id' => 1,
            ],
            [
                'user_id' => 1,
                'ders_id' => 2,
            ],
            [
                'user_id' => 2,
                'ders_id' => 1,
            ],
            [
                'user_id' => 3,
                'ders_id' => 1,
            ],
            [
                'user_id' => 4,
                'ders_id' => 1,
            ],
            [
                'user_id' => 2,
                'ders_id' => 2,
            ],
            [
                'user_id' => 3,
                'ders_id' => 2,
            ],
            [
                'user_id' => 3,
                'ders_id' => 3,
            ],
            [
                'user_id' => 4,
                'ders_id' => 3,
            ],
            [
                'user_id' => 4,
                'ders_id' => 4,
            ],
            [
                'user_id' => 5,
                'ders_id' => 4,
            ],
            [
                'user_id' => 5,
                'ders_id' => 5,
            ],
            [
                'user_id' => 6,
                'ders_id' => 5,
            ],
            [
                'user_id' => 6,
                'ders_id' => 6,
            ],
            [
                'user_id' => 7,
                'ders_id' => 6,
            ],
            [
                'user_id' => 7,
                'ders_id' => 7,
            ],
            [
                'user_id' => 8,
                'ders_id' => 7,
            ],
            [
                'user_id' => 8,
                'ders_id' => 8,
            ],
        ]);
    }
}
