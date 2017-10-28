<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'role','name', 'email', 'password','avatar','gender','provider','api_info','access_token','provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public static function generate_avatar($data){

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

    public static function insertInfo($array = []){
        $users = new User();
        if(is_array($array)){
            $users->name        = $array['name'];
            $users->email       = $array['email'];
            $users->avatar      = $array['avatar'];
            $users->gender      = $array['gender'];
            $users->provider    = $array['provider'];
            $users->api_info    = $array['api_info'];
            $users->provider_id = $array['provider_id'];
        }
        $users->save();
        return self::where('provider_id',$array['provider_id']);
    }
    protected $hidden = [
        'password', 'remember_token',
    ];
}
