<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use App\Follow;
use App\Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\RepliedToFollow;

class FollowController extends Controller
{

    public function follow(Request $request)
    {
        if($request->ajax()){

            $rules = [
                'follower_id' => 'required|numeric'
            ];

            $this->validate($request, $rules);

            $follower_id = $request->follower_id;

            $auth_user = Auth::user();

            $user = User::find($follower_id);

            $user->notify(new RepliedToFollow($auth_user));

            $insert = Notify::where('notifiable_id', $follower_id)->first();

            $insert->to = $auth_user->id;


            return ($insert->save()) ? response(['ok' => 1],200) :  response(null, 404);
        }
    }

    /**
     * Unfollow users
     */

    public function unfollow(Request $request)
    {
        if($request->ajax()){

            $rules = [
                'follower_id' => 'required|numeric'
            ];

            $this->validate($request, $rules);

            $follower_id = $request->follower_id;

            $user_id = get_auth_id();

            $follower = check_follower_or_not($follower_id, $user_id);

            $delete = $follower->delete();

            return ($delete) ? response(['ok' => 1], 200) : response(null, 404);
        }

    }

    /**
     * Cancal sended OR accept
     */

    public function cancel(Request $request)
    {
        if($request->ajax()){

            $rules = [
                'follower_id' => 'required|numeric'
            ];

            $this->validate($request, $rules);

            $follower_id = $request->follower_id;

            if($request->check){

                $delete = Notify::where('to',$follower_id)
                                ->delete();
            }else{

                $delete = Notify::where('notifiable_id',$follower_id)
                                ->delete();
            }

            return ($delete) ? response(['ok' => 1], 200) : response(null, 404);
        }
    }

    /**
     * Accept follow Request
     */
    public function accept(Request $request)
    {
        if($request->ajax()){

            $rules = [
                'follower_id' => 'required|numeric'
            ];

            $this->validate($request, $rules);

            $follower_id = $request->follower_id;

            $user = \Auth::user();

            $data = [];

            $user_notifications = Notify::where('to',$follower_id)
                                        ->get();

            foreach ($user_notifications as $notification) {
                $decode_data = json_decode($notification->data);
                $data['follower_name']  = $decode_data->follower_name;
                $data['follower_id']    = $decode_data->follower_id;
            }


            $delete = Notify::where('to',$follower_id)
                            ->delete();

            if($delete){
                $follow = new Follow();

                $follow->user_id = $user->id;
                $follow->follower_id = $data['follower_id'];
                $avatar = User::find($follower_id);

                $data = [
                    "ok" => 1,
                    "name" => $data['follower_name'],
                    "id" => $data['follower_id'],
                    "avatar" => $avatar->avatar
                ];

                return $follow->save() ? response($data, 200) : response(null, 404);
            }
        }


    }

}
