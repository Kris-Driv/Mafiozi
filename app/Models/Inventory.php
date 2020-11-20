<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    const WEAPON_STORE      = 1;
    const VEHICLE_STORE     = 2;
    const PROPERTY_STORE    = 3;
    
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function properties()
    // {
    //     return $this->hasMany(Property::class);
    // }

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }

    // public function vehicles()
    // {
    //     return $this->hasMany(Vehicle::class);
    // }

    public function getCount($item) 
    {
        if($item instanceof Weapon) {
            if($w = $this->weapons->where('name', $item->name)->first()) {
                return $w->amount;
            }
            return 0;
        }
        throw new \InvalidArgumentException("Unknown item $item");
    }

}
