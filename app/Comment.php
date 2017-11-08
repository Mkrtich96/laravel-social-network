<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $table = "comments";

    protected $fillable = ['comment','post_id','user_id','parent_id'];

    public function user(){

        return $this->belongsTo('App\User')->select(['id', 'name','avatar']);
    }

    public function post(){

        return $this->belongsTo('App\Post');
    }

    public function parent(){

        return $this->belongsTo('App\Comment', 'parent_id');
    }

}
