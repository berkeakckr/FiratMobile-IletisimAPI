<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
             [
                 'name'=>'Berke Akçakır',
                 'email'=>'190290054@firat.edu.tr',
                 'password'=>bcrypt('berke123'),
                 'type'=>0,
                 'remember_token'=>Str::random(10),
             ],
            [
                'name'=>'Mustafa Ulaş',
                'email'=>'mustafaulas@firat.edu.tr',
                'password'=>bcrypt('mustafa123'),
                'type'=>1,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Sidar Polat',
                'email'=>'sidarpolat@firat.edu.tr',
                'password'=>bcrypt('sidar123'),
                'type'=>0,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Hakan Güler',
                'email'=>'hakanguler@firat.edu.tr',
                'password'=>bcrypt('hakan123'),
                'type'=>1,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Tuncay Forma',
                'email'=>'tuncayforma@firat.edu.tr',
                'password'=>bcrypt('tuncay123'),
                'type'=>0,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Emre Çolak',
                'email'=>'emrecolak@firat.edu.tr',
                'password'=>bcrypt('emre123'),
                'type'=>1,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Nurullah Arslan',
                'email'=>'nurullaharslan@gmail.com',
                'password'=>bcrypt('nurullah123'),
                'type'=>0,
                'remember_token'=>Str::random(10),
            ],
            [
                'name'=>'Sinem Akyol',
                'email'=>'sinemakyol@gmail.com',
                'password'=>bcrypt('sinem123'),
                'type'=>1,
                'remember_token'=>Str::random(10),
            ],

        ]);

    }
}
