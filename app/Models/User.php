<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * This is how the new User should be registered
     * Pass necessary values, name, username, email and password through
     * $data parameter. These values will not be validated
     * 
     * @param array $data
     */
    public static function registerNew(array $data): ?User
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        Stat::createDefaults($user);
        Bank::createDefaults($user);
        Inventory::create([
            'user_id' => $user->id
        ]);

        return $user;
    }

    /**
     * Relationship to Bank model
     */
    public function bank()
    {
        return $this->hasOne(Bank::class);
    }

    /**
     * Relationship to Stat model
     */
    public function stats()
    {
        return $this->hasMany(Stat::class);
    }

    /**
     * Returns Stat model, not before calling update
     */
    public function getStat(string $name) : Stat
    {
        $stat = $this->stats->where('type', $name)->first();
        $stat->updateValue();
        return $stat;
    }

    /**
     * Returns money Stat value
     */
    public function getMoney(): int
    {
        return $this->getStat('money')->value;
    }

    /**
     * Substracts value for money Stat
     */
    public function takeMoney(int $value)
    {
        $stat = $this->getStat('money');
        $stat->value -= $value;
        $stat->save();
    }

    /**
     * Adds value for money Stat
     */
    public function giveMoney(int $value)
    {
        $stat = $this->getStat('money');
        $stat->value += $value;
        $stat->save();
    }

    /**
     * Returns next update time for given stat
     * @link \App\Models\Stat@getNextUpdate
     * 
     * @param string $statName
     * @param bool $delta = false
     */
    public function getNextUpdate(string $statName, bool $delta = false)
    {
        $stat = $this->getStat($statName);
        if ($stat) {
            return $stat->getNextUpdate($delta);
        }
        throw new \InvalidArgumentException("Stat '{$stat->type}:{$stat->id}' does not exist for specific user $this");
    }


    /**
     * Returns absolute path to profile image
     * 
     * @return string
     */
    public function getProfileImage() : string
    {
        if ($this->profile_image === null) {
            return "img/default_avatar.jpg";
        } else {
            return "img/profile/$this->profile_image";
        }
    }

    /**
     * Check requirements if player is capable of given Job
     */
    public function canDoJob(Job $job): bool
    {
        $level = $this->getLevel();
        if ($job->level <= $this->getLevel()) {
            if ($job->energy <= $this->getStat('energy')->value) {
                // Check inventory requirement

                // And mafia requirement
                // TODO
                return true;
            }
        }

        return false;
    }

    /**
     * Relationship to Message model
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /** LEVELING SYSTEM */

    public static function getLevelFromXP(int $xp): int
    {
        return floor(25 + sqrt(625 + 100 * $xp)) / 50;
    }

    public static function getXPFromLevel(int $level): int
    {
        return 25 * $level * $level - 25 * $level;
    }

    public function getLevel(): int
    {
        $level = User::getLevelFromXP($this->getXP());
        return $level;
    }

    public function getXP(): int
    {
        return $this->getStat('xp')->value;
    }

    public function setXP(int $xp): void
    {
        $stat = $this->getStat('xp');
        $stat->value = $xp;
        $stat->save();
        // Check level-up
        $level = $this->getStat('level');
        if ($level->value < $this->getLevel()) {
            $this->trigger('level-up');
        }
        $level->value = $this->getLevel();
        $level->save();
    }

    public function getXPToNextLevel(): int
    {
        return self::getXPFromLevel($this->getLevel() + 1);
    }

    public function trigger(string $event): bool
    {
        echo "Player level up";
        return true;
    }

    public function addXP(int $xp): void
    {
        $this->setXP($this->getXP() + $xp);
    }

    /**
     * Relationship to Inventory model
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
