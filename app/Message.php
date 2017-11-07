<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = ['from', 'to', 'seen', 'message'];

    public $primarykey = 'to';


    public function user() {
        return $this->belongsTo('App\User', 'from');
    }

}
