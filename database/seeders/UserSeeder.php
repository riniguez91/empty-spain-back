<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\timezone;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        //DB::table('usuario')->delete(); borrar tabla antes de insertar
        //Añadir 3 usuarios de ejemplo
        for($i=0; $i<3; $i++){
            DB::table('usuario')->insert([
                'email' => $faker->Firstname.'@gmail.com',
                'name' => $faker->Firstname,
                'surnames' => $faker->Lastname,
                'password' => Hash::make('password'),
                'role' => 1,
                'updated_at' => date_create("now", timezone_open("Europe/Warsaw")),
                'created_at' =>  date_create("now", timezone_open("Europe/Warsaw"))
            ]);
        }
    }
}
