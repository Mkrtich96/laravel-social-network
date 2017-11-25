<?php

namespace App;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Billable;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'admin',
        'password',
        'avatar',
        'gender',
        'provider',
        'stripe_account_id',
        'api_info',
        'access_token',
        'provider_id',
        'stripe_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function getCreatedAtAttribute()
    {
        return parseCreatedAt($this->attributes['created_at']);
    }

    public function galleries()
    {

        return $this->hasMany('App\Gallery');
    }

    public function posts()
    {

        return $this->hasMany('App\Post');
    }

    public function comments(){

        return $this->hasMany('App\Comment');
    }

    public function followers() {

        return $this->hasMany('App\Follow','user_id');
    }

    public function messages() {

        return $this->hasMany('App\Message', 'to');
    }

    public function messagesfrom(){

        return $this->hasMany('App\Message', 'from');
    }

    public function conversations(){

        return $this->belongsToMany('App\Conversations','conversations_users','user_id','conversations_id');
    }

    public function conversation(){

        return $this->hasMany('App\Conversations','owner_id');
    }

    public function products(){

        return $this->hasMany('App\Product');
    }

    public function orders(){

        return $this->hasMany('App\Order');
    }

}
