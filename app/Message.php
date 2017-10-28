<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public $primarykey = 'to';

    public static function get_chat_history($from,$to){
        $get =  Message::where(function($query) use ($from, $to) {
            $query->where('to',$to)
                ->where('from',$from);
        })->orWhere(function($query) use ($from, $to) {
            $query->where('to',$from)
                ->where('from',$to);
        })->get();

        return $get;
    }
}
