<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    protected $table = 'conversations';

    protected $fillable = ['name', 'owner_id'];


    public function users(){

        return $this->belongsToMany('App\User','conversations_users','conversations_id','user_id');
    }

    public function owner(){

        return $this->belongsTo('App\User','owner_id');
    }

    public function messages(){

        return $this->hasMany('App\Message','conversations_id');
    }

}
