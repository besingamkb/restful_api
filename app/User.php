<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addPhoneNumber(Phonenumber $phonenumber)
    {
        $this->phonenumbers()->save($phonenumber);
    }

    /**
     * Returns the related phonenumbers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phonenumbers()
    {
        return $this->hasMany(Phonenumber::class);
    }

    public function addClient(Client $client)
    {
        $this->clients()->save($client);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
