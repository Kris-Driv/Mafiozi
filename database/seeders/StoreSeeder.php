<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->insert([
            "name" => "weapons",
            "inventory_id" => Store::WEAPON_STORE
        ]);
        DB::table('stores')->insert([
            "name" => "vehicle",
            "inventory_id" => Store::VEHICLE_STORE
        ]);
        DB::table('stores')->insert([
            "name" => "property",
            "inventory_id" => Store::PROPERTY_STORE
        ]);
    }
}
