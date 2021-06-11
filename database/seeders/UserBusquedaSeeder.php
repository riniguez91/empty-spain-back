<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserBusquedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Carga los datos del SQL
        $path = base_path() . '/user_busqueda.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
