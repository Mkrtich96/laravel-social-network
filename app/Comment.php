<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = "comments";


    public function user(){

        return $this->belongsTo('App\User','from_user');
    }

    public function posts(){

        return $this->belongsTo('App\Post','on_post');
    }

}
