<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('ders')->truncate();
        DB::table('ders')->insert([
            [
                'bolum_id' => 1,
                'ders_adi' => "Dağıtık Yazılım Mühendisliği",
                'akademisyen_id' => 2,
            ],
            [
                'bolum_id' => 1,
                'ders_adi' => "Veri İletişimi Ve Bilgisayar Ağları",
                'akademisyen_id' => 2,
            ],
            [
                'bolum_id' => 1,
                'ders_adi' => "Uygulamalı Sinir Ağları",
                'akademisyen_id' => 4,
            ],
            [
                'bolum_id' => 2,
                'ders_adi' => "Algoritma ve Programlama",
                'akademisyen_id' => 4,
            ],
            [
                'bolum_id' => 2,
                'ders_adi' => "Otomata",
                'akademisyen_id' => 6,
            ],
            [
                'bolum_id' => 3,
                'ders_adi' => "Elektrik Makineleri",
                'akademisyen_id' => 6,
            ],
            [
                'bolum_id' => 3,
                'ders_adi' => "Elektrik Devreleri",
                'akademisyen_id' => 8,
            ],
            [
                'bolum_id' => 4,
                'ders_adi' => "Makine Mühendisliğine Giriş",
                'akademisyen_id' => 8,
            ],

        ]);
    }
}
