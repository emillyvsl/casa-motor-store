<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function addresses()
    {
        return $this->hasMany(Addresses::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function cart()
    {
        return $this->hasOne(Carts::class);
    }
}
