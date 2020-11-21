<?php
namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class WeaponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Baseball bat
        DB::table('weapons')->insert([
            "name" => "bat",
            "attack" => 1,
            "defense" => 0,
            "image" => "img/weapon/bat.png",
            "price" => 1000,
            "costs" => 0,
            "level" => 1,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Crowbar
        DB::table('weapons')->insert([
            "name" => "crowbar",
            "attack" => 1,
            "defense" => 1,
            "image" => "img/weapon/crowbar.png",
            "price" => 5000,
            "costs" => 0,
            "level" => 2,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Glock 19
        DB::table('weapons')->insert([
            "name" => "glock-19",
            "attack" => 2,
            "defense" => 1,
            "image" => "img/weapon/glock.png",
            "price" => 35000,
            "costs" => 500,
            "level" => 2,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Colt
        DB::table('weapons')->insert([
            "name" => "colt-19",
            "attack" => 2,
            "defense" => 2,
            "image" => "img/weapon/colt.png",
            "price" => 20000,
            "costs" => 2000,
            "level" => 3,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Shotgun
        DB::table('weapons')->insert([
            "name" => "shotgun",
            "attack" => 2,
            "defense" => 4,
            "image" => "img/weapon/shotgun.png",
            "price" => 50000,
            "costs" => 0,
            "level" => 4,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Uzi
        DB::table('weapons')->insert([
            "name" => "uzi",
            "attack" => 3,
            "defense" => 3,
            "image" => "img/weapon/uzi.png",
            "price" => 65000,
            "costs" => 0,
            "level" => 4,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // AK47
        DB::table('weapons')->insert([
            "name" => "ak-47",
            "attack" => 6,
            "defense" => 4,
            "image" => "img/weapon/ak47.png",
            "price" => 100000,
            "costs" => 0,
            "level" => 5,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // Mini-Gun
        DB::table('weapons')->insert([
            "name" => "minigun",
            "attack" => 12,
            "defense" => 10,
            "image" => "img/weapon/minigun.jpg",
            "price" => 350000,
            "costs" => 8000,
            "level" => 6,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // M82
        DB::table('weapons')->insert([
            "name" => "m82",
            "attack" => 16,
            "defense" => 12,
            "image" => "img/weapon/m82.png",
            "price" => 650000,
            "costs" => 12500,
            "level" => 7,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
        // RPG 7
        DB::table('weapons')->insert([
            "name" => "rpg-7",
            "attack" => 22,
            "defense" => 14,
            "image" => "img/weapon/rpg.png",
            "price" => 1000000,
            "costs" => 25000,
            "level" => 8,
            "inventory_id" => Inventory::WEAPON_STORE
        ]);
    }
}
