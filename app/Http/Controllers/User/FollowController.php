<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreAcceptFollow;
use App\Http\Requests\StoreCancelFollow;
use App\Http\Requests\StoreUnfollow;
use Auth;
use App\User;
use App\Follow;
use App\Notify;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFollow;
use App\Http\Controllers\Controller;
use App\Notifications\RepliedToFollow;

class FollowController extends Controller
{

    public function follow(StoreFollow $request)
    {

        $follower_id    = $request->notifiable_id;
        $auth_user      = get_auth();
        $user = User::find($follower_id);

        if(!is_null($user)){

            $user->notify(new RepliedToFollow($auth_user));

            return response([
               'status' =>  'success',
            ], 200);
        }

        return response([
            'status'    =>  'fail',
            'message'   =>  'User not finded. Error 404.'
        ], 404);

    }

    /**
     * Unfollow users
     */

    public function unfollow(StoreUnfollow $request)
    {

        $user_id = get_auth('id');
        $follower = check_follower_or_not($request->follower_id, $user_id);

        $delete = $follower->delete();

        if($delete){

            return response([
                'status'    =>  'success'
            ], 200);
        }

        return response([
            'status'    =>  'fail',
            'message'   =>  'Unfollow failed. Error 404!'
        ], 404);
    }

    /**
     * Cancal sended OR accept
     */

    public function cancel(StoreCancelFollow $request)
    {

        if ($request->accidentally) {

            $notification = Notify::where('to', $request->follower_id)
                                    ->first();
        } else {
            $notification = Notify::where('notifiable_id', $request->follower_id)
                                    ->first();
        }

        $delete = $notification->delete();

        if ($delete) {

            return response(['status' => 'success'], 200);
        }

        return response([
            'status' => 'fail',
            'message' => 'Error with deleting notification. 404!'
        ], 404);
    }

    /**
     * Accept follow Request
     */
    public function accept(StoreAcceptFollow $request)
    {

        $user_notification = Notify::where('to', $request->follower_id)->first();

        $decode_data    = json_decode($user_notification->data);
        $follower_name  = $decode_data->follower_name;
        $follower_id    = $decode_data->follower_id;

        $delete = $user_notification->delete();

        if ($delete) {

            $follow = new Follow();

            $follow->user_id        = get_auth('id');
            $follow->follower_id    = $follower_id;
            $user_info      = User::find($request->follower_id);
            $user_avatar    =   generate_avatar($user_info);

            if ($follow->save()) {

                return response([
                    "status" => 'success',
                    "name" => $follower_name,
                    "id" => $follower_id,
                    "avatar" => $user_avatar
                ], 200);
            }

            return response([
                'status' => 'fail',
                'message' => 'Follower not accepted. Connection error!'
            ], 404);
        }

        return response([
            'status' => 'fail',
            'message' => 'Error with deleting notification.'
        ], 404);

    }

}
