<?php

use Illuminate\Support\Facades\Auth;
use App\Follow;
use Carbon\Carbon;

if( !function_exists('check_follower_or_not') ){

    function check_follower_or_not($follower_id, $followed_id){

        $follow = Follow::where(function ($query) use ($follower_id, $followed_id) {
            $query->where([
                        ['user_id','=',$follower_id],
                        ['follower_id','=',$followed_id]
                    ]);
        })->orWhere(function ($query) use ($follower_id, $followed_id) {
            $query->where([
                        ['user_id','=',$followed_id],
                        ['follower_id','=',$follower_id]
                    ]);
        })->first();

        if(!is_null($follow)){

            return $follow;
        }

        return null;

    }
}

if( !function_exists('get_auth_id') ){

    function get_auth($id = null) {

        $user   =   Auth::user();

        if(!is_null($user)){

            if($id == 'id'){
                return $user->id;
            }
            return $user;
        }
        return null;
    }
}

if( !function_exists('parseCreatedAt') ){

    function parseCreatedAt($data){
        return Carbon::parse($data)->format('M-d-Y, H:i');
    }
}

