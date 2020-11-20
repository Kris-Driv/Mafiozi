<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    /**
     * Bank fee for deposit transaction
     */
    const DEFAULT_FEE = 2.1;

    /**
     * Seed database for User model
     */
    public static function createDefaults(User $user) 
    {
        DB::table('banks')->insert([
            'user_id' => $user->id
        ]);
    }
    
    /**
     * Relationship to User model
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This defines how much the bank will take off from every deposit transaction
     * TODO: Make this individual for each User
     */
    public function getFee() : float 
    {
        return $this->fee ?? self::DEFAULT_FEE;
    }

}
