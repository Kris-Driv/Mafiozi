<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::registerNew([
            "username" => "Admin",
            "email" => "admin@localhost",
            "password" => "admin"
        ]);
    }
}