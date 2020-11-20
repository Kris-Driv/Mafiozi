<?php

namespace App;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function registerNew(array $data) : ?User {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        Stat::createDefaults($user);
        Bank::createDefaults($user);
        Inventory::create([
            'user_id' => $user->id
        ]);

        return $user;
    }

    public function bank()
    {
        return $this->hasOne(Bank::class);
    }
    
    public function stats() 
    {
        return $this->hasMany(Stat::class);
    }
    
    public function getStat(string $name) {
        $stat = $this->stats->where('type', $name)->first();
        $stat->updateValue();
        return $stat;
    }

    public function getMoney() : int {
        return $this->getStat('money')->value;
    }

    public function takeMoney(int $value) {
        $stat = $this->getStat('money');
        $stat->value -= $value;
        $stat->save();
    }

    public function giveMoney(int $value) {
        $stat = $this->getStat('money');
        $stat->value += $value;
        $stat->save();
    }
    
    public function getNextUpdate(string $statName, bool $delta = false) {
        $stat = $this->getStat($statName);
        if($stat) {
            return $stat->getNextUpdate($delta);
        }
        throw new \InvalidArgumentException("Stat '{$stat->type}:{$stat->id}' does not exist for specific user $this");
    }
    
    public function getProfileImage() 
    {
        if($this->profile_image === null) 
        {
            return "img/default_avatar.jpg";
        } 
        else 
        {
            return "img/profile/$this->profile_image";
        }
    }

    public function canDoJob(Job $job) : bool
    {
        $level = $this->getLevel();
        if($job->level <= $this->getLevel()) {
            if($job->energy <= $this->getStat('energy')->value) {
                // Check inventory requirement

                // And mafia requirement
                // TODO
                return true;
            }
        }

        return false;
    }

    public function messages() 
    {
        return $this->hasMany(Message::class);
    }

    /** LEVELING SYSTEM */

    public static function getLevelFromXP(int $xp) : int
    {
        return floor(25 + sqrt(625 + 100 * $xp)) / 50;
    }

    public static function getXPFromLevel(int $level) : int
    {
        return 25 * $level * $level - 25 * $level;
    }

    public function getLevel() : int 
    {
        $level = User::getLevelFromXP($this->getXP());
        return $level;
    }

    public function getXP() : int 
    {
        return $this->getStat('xp')->value;
    }

    public function setXP(int $xp) : void 
    {
        $stat = $this->getStat('xp');
        $stat->value = $xp;
        $stat->save();
        // Check level-up
        $level = $this->getStat('level');
        if($level->value < $this->getLevel()) {
            $this->trigger('level-up');
        }
        $level->value = $this->getLevel();
        $level->save();
    }

    public function getXPToNextLevel() : int {
        return self::getXPFromLevel($this->getLevel() + 1);
    }

    public function trigger(string $event) : bool {
        echo "Player level up";
        return true;
    }

    public function addXP(int $xp) : void
    {
        $this->setXP($this->getXP() + $xp);
    }

    /** --- INVENTORY --- */

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

}
