<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(JobsSeeder::class);
        $this->call(StoreInventorySeeder::class);
        $this->call(StoreSeeder::class);
        $this->call(WeaponSeeder::class);

        $this->call(UserSeeder::class);
    }
}
