<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = ['from', 'to', 'seen', 'message'];

    public $primarykey = 'to';

    protected function getCreatedAtAttribute()
    {
        return parseCreatedAt($this->attributes['created_at']);
    }

    public function user() {

        return $this->belongsTo('App\User', 'from');
    }

    public function conversation() {

        return $this->belongsTo('App\Conversations', 'conversations_id');
    }

}
