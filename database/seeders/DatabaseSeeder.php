<?php

namespace Database\Seeders;

use App\Http\Controllers\Api\DatabaseController;
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
        //php artisan migrate:fresh
        //php artisan passport:install
        //php artisan db:seed --class=DatabaseSeeder

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('bolum')->truncate();
        DB::table('ders')->truncate();
        DB::table('user_bolum')->truncate();
        DB::table('user_ders')->truncate();


        $this->call([
            UserSeeder::class,
            BolumSeeder::class,
            DersSeeder::class,
            UserBolumSeeder::class,
            UserDersSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DatabaseController::dersleriEkle();

    }
}
