<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->insert([
            "name" => "Robbery",
            "energy" => 3,
            "mafia" => 0,
            "level" => 0,
            "rm_min" => 300,
            "rm_max" => 500,
            "xp" => 3
        ]);
        DB::table('jobs')->insert([
            "name" => "Theft with invasion",
            "energy" => 5,
            "mafia" => 0,
            "level" => 2,
            "rm_min" => 600,
            "rm_max" => 1000,
            "xp" => 5
        ]);
    }
}
