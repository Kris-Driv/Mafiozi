<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    const WEAPON_STORE      = 1;
    const VEHICLE_STORE     = 2;
    const PROPERTY_STORE    = 3;
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

}
