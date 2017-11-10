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

    public function user(){

        return $this->belongsTo('App\User');
    }

}
