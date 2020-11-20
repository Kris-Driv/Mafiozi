<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    
    protected $fillable = [
        "value", "type", "user_id", "max", "grow_by", "update_interval",
        "delta_time", "last_updated"
    ];
    
    const DEFAULTS = [
        "money" => [
            "value" => 500,
            "update_interval" => 3600,
            "grow_by" => 0
        ],
        "health" => [
            "value" => 100,
            "max" => 100,
            "update_interval" => 60
        ],
        "energy" => [
            "value" => 100,
            "max" => 100,
            "update_interval" => 60
        ],
        "stamina" => [
            "value" => 100,
            "max" => 100,
            "update_interval" => 60
        ],
        "xp" => [
            "value" => 0,
            "max" => 100,
            "update_interval" => 0,
            "grow_by" => 0
        ],
        "level" => [
            "value" => 1,
            "update_interval" => 0,
            "grow_by" => 0
        ],
        "mafia" => [
            "value" => 0,
            "update_interval" => 0,
            "grow_by" => 0,
            "max" => 0
        ]
    ];
    
    /**
     * Seeds database for User model
     * 
     * @param User $user
     */
    public static function createDefaults(User $user) {
        foreach(self::DEFAULTS as $type => $d) {
            (new Stat(array_merge(["type" => $type, "user_id" => $user->id], $d)))->save();
        }
    }
    
    /**
     * Returns time in seconds when next recalculation will be necessary
     * Useful for countdowns in UI
     * Pass true in params if you need the relative time till update
     * 
     * @param bool $delta = false
     */
    public function getNextUpdate(bool $delta = false) {
        if($this->last_updated !== null) 
        {
            if($this->update_interval <= 0) return 0;
            $r = $this->last_updated + $this->update_interval - $this->delta_time;
            return $delta ? $r - time() : $r;
        }   
         
        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $this->updated_at);
        if(!$d) {
            throw new \InvalidStateException("Stat '$this->id' has incorrect time: {$this->updated_at}");
        }
        $d = $d->getTimestamp();
        $r = $d + $this->update_interval - $this->delta_time;
        return $delta ? $r - time() : $r;
    }
    
    /**
     * Stats are updating upon request using the delta between current and last time updated
     * Returns true on successful update or false otherwise, meaning update was not necessary
     * 
     * @return bool
     */
    public function updateValue() : bool {
        if($this->value >= $this->max) return false;
        
        if($this->update_interval > 0) {
            $delta = $this->getNextUpdate(true);
            if($delta <= 0) {
                $delta *= -1;
                // At least one update should occur
                $m = floor($delta / $this->update_interval) + 1;
                $delta = $delta % $this->update_interval;
                $add = $this->grow_by * $m;
                
                $this->value += $add;
                $this->delta_time = $this->value >= $this->max ? 0 : $delta;

                // cap the value to it's maximum
                $this->last_updated = time();
                $this->setValue(max(0, min($this->value, $this->max)));

                return true;
            }
        }
        return false;
    }
    
    /**
     * Set new value
     * @param int $value
     */
    public function setValue(int $value) {
        $this->value = $value;
        
        $this->save();
    }
    
    /**
     * Relationship to User model
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
