<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected function getCreatedAtAttribute()
    {
        return parseCreatedAt($this->attributes['created_at']);
    }


    protected $fillable = ['user_id','image'];
}
