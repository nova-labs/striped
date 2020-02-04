<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;

class Member extends Authenticatable
{


    public function sponsor()
    {
        return $this->hasOne('App\Member','id','sponsor_id' );
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function stewards()
    {
        return $this->groups()->where('name', 'like', '[steward]%');
    }

    public function equipment()
    {
        return $this->groups()->where('name', 'like', '[equipment]%');
    }

    public function plaingroups()
    {
        return $this->groups()->where('name', 'not like', '[%');
    }

    public function family_lead()
    {
        return $this->hasOne('App\Member','id','family_primary_member_id' );
    }

    public function family_members()
    {
        return $this->hasMany('App\Member','family_primary_member_id', 'id' );
    }

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
}
