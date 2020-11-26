<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for($i = 0; $i < 20; $i++) {
            Message::create([
                "user_id" => User::all()->random()->id,
                "message" => $faker->sentence
            ]);
        }
    }
}
