<?php

namespace App;

use App\Navbar;
use App\NavGroup;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'password',
    ];

    public function navbar()
    {
        return $this->belongsToMany(Navbar::class)->withTimestamps();
    //    return $this->belongsToMany(Navbar::class)::performJoin(Navbar::find(1)->id);
    // return $this->belongsToMany(Navbar::class);
    }

    // public function navgroup()
    // {
    //     return $this->navbar()->hasOne(NavGroup::class);
    //     // return $this->navbar()->navgroup();
    // }
}
