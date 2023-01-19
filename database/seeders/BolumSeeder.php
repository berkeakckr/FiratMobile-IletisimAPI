<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BolumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('bolum')->truncate();
        DB::table('bolum')->insert([
            [
                'bolum_adi' => "Yazılım Mühendisliği",
            ],
            [
                'bolum_adi' => "Bilgisayar Mühendisliği",
            ],
            [
                'bolum_adi' => "Elektrik-Elektronik Mühendisliği",
            ],
            [
                'bolum_adi' => "Makine Mühendisliği",
            ],

        ]);
    }
}
