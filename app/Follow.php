<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = "followers";

    protected function getCreatedAtAttribute()
    {
        return parseCreatedAt($this->attributes['created_at']);
    }

    public function userLeft(){

        return $this->belongsTo('App\User', 'user_id');
    }

    public function userRight(){

        return $this->belongsTo('App\User', 'follower_id');
    }

}
