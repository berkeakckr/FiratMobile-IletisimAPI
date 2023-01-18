<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //php artisan db:seed --class=DatabaseSeeder

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bolum')->truncate();
        DB::table('ders')->truncate();

        DB::table('user_bolum')->truncate();
        DB::table('user_ders')->truncate();


        $this->call([
            BolumSeeder::class,
            DersSeeder::class,
            UserBolumSeeder::class,
            UserDersSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
