<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = "followers";



    public static function check_follower_or_not($follower_id, $followed_id){

        $follow = Follow::where(function($query) use ($follower_id, $followed_id) {
            $query->where('user_id',$follower_id)
                    ->where('follower_id',$followed_id);
        })->orWhere(function($query) use ($follower_id, $followed_id) {
            $query->where('user_id',$followed_id)
                    ->where('follower_id',$follower_id);
        })->first();

        return $follow;
    }

    public static function crtFollowBtn($class, $data_id, $text){
        $button = "<button class='btn btn-". $class ."' data-id='". $data_id ."'>" . $text . "</button>";
        return $button;
    }

}
