<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";

    protected $fillable = ['user_id','text','status'];

    public function user() {

        return $this->belongsTo('App\User');
    }

}
