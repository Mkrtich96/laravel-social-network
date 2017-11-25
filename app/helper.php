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

if( !function_exists('generate_avatar') ){

    function generate_avatar($data){

        $avatar = null;

        if(is_null($data->avatar)) {
            if (!$data->gender){
                $avatar = asset('images/avatars/male.gif');
            }else{
                $avatar = asset('images/avatars/female.gif');
            }
        }else{
            $avatar = asset('images/' .$data->id . '/' . $data->avatar);
        }

        return $avatar;
    }
}

if( !function_exists('pointingPrice') ){
    function pointingPrice($number){

        $new = strlen($number) - 2;
        return  substr_replace($number, '.', $new, 0);
    }
}