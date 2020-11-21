<?php
namespace Database\Seeders;

use App\Models\Store;
use App\Models\Inventory;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inventories')->insert([
            "id" => Inventory::WEAPON_STORE,
            "store_id" => Store::WEAPON_STORE
        ]);
        DB::table('inventories')->insert([
            "id" => Inventory::VEHICLE_STORE,
            "store_id" => Store::VEHICLE_STORE
        ]);
        DB::table('inventories')->insert([
            "id" => Inventory::PROPERTY_STORE,
            "store_id" => Store::PROPERTY_STORE
        ]);
    }
}
