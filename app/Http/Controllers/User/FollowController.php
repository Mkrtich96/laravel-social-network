<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Follow;
use Auth;
use App\User;
use App\Notifications\RepliedToFollow;

class FollowController extends Controller
{
    public function follow($user_id){

        $user = User::find($user_id);

        $user->notify(new RepliedToFollow(Auth::user()));

        return 1;
    }

    /**
     * Unfollow users
     */

    public function unfollow($follower_id){

        $user_id = Auth::user()->id;

        $follower = Follow::check_follower_or_not($follower_id,$user_id);

        $follower->delete();

        return 1;
    }

    /**
     * Cancal sended OR accept
     */

    public function cancel(Request $request,$follower_id){
        $check = true;

        if($request->check){
            $user = \Auth::user();
        }else{
            $user   = User::find($follower_id);
            $check  = false;
        }
        foreach ($user->notifications as $notification) {
            if(!$check){
                if($notification->notifiable_id == $follower_id){
                    $notification->delete();
                    break;
                }
            }else{
                if($notification->data['follower_id'] == $follower_id){
                    $notification->delete();
                    break;
                }
            }
        }
        return 1;
    }

    /**
     * Accept follow Request
     */
    public function accept($follower_id){
        $user = \Auth::user();
        $data = [];
        foreach ($user->notifications as $notification) {
            if($notification->data['follower_id'] == $follower_id){
                $data['follower_name']  = $notification->data['follower_name'];
                $data['follower_id']    = $notification->data['follower_id'];
                $notification->delete();
                break;
            }
        }
        $follow = new Follow();
        $follow->user_id        = $user->id;
        $follow->follower_id    = $data['follower_id'];
        $avatar = User::find($follower_id);
        if($follow->save()){
            return [
                "ok"    => 1,
                "name"  => $data['follower_name'],
                "id"    => $data['follower_id'],
                "avatar"=> $avatar->avatar
            ];
        }
    }



}
