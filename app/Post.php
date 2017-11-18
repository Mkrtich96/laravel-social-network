<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";

    protected $fillable = ['user_id','text','status'];

    protected function getCreatedAtAttribute()
    {
        return parseCreatedAt($this->attributes['created_at']);
    }

    public function user() {

        return $this->belongsTo('App\User');
    }

    public function comments(){

        return $this->hasMany('App\Comment');
    }

}
