<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = "followers";

    public function users(){
        return $this->belongsTo('App\User');
    }

}
