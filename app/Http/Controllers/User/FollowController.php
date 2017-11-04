<?php

namespace App\Http\Controllers\User;

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

        $user->notify(new RepliedToFollow($auth_user));
        $change = Notify::where('notifiable_id', $follower_id)->first();


        if(!is_null($change)){

            $change->to =   $auth_user->id;
            $update     =   $change->save();
            if($update){
                return response(['status' => 'fail','message'   => 'Notification doesn\'t created.'], 404);
            }

            return response([
                'message'   => 'Notification doesn\'t created.'
            ], 404);

        }

        return response([
            'status'    => 'fail',
        ], 404);

    }

    /**
     * Unfollow users
     */

    public function unfollow(StoreFollow $request)
    {
        $follower_id = $request->follower_id;
        $user_id = get_auth('id');
        $follower = check_follower_or_not($follower_id, $user_id);
        $delete = $follower->delete();

        return ($delete) ? response(['ok' => 1], 200) : response(null, 404);
    }

    /**
     * Cancal sended OR accept
     */

    public function cancel(StoreFollow $request)
    {

        $follower_id = $request->follower_id;

        if ($request->check) {

            $notification = Notify::where('to', $follower_id)
                                    ->first();
        } else {
            $notification = Notify::where('notifiable_id', $follower_id)
                                    ->first();
        }

        if (!is_null($notification)) {

            $remove = $notification->delete();

            if ($remove) {

                return response(['status' => 'success'], 200);
            } else {
                return response([
                    'status' => 'fail',
                    'message' => 'Error with deleting notification.'
                ], 404);
            }
        }

        return response([
            'status' => 'fail',
            'message' => 'Notification does not exists.'
        ], 422);

    }

    /**
     * Accept follow Request
     */
    public function accept(StoreFollow $request)
    {

        $data = array();
        $user_notification = Notify::where('to', $request->follower_id)->first();


        if (isset($user_notification)) {

            $decode_data = json_decode($user_notification->data);
            $data['follower_name'][] = $decode_data->follower_name;
            $data['follower_id'][] = $decode_data->follower_id;
        }

        $notification = Notify::where('to', $request->follower_id)->first();


        if ($notification) {

            $remove = $notification->delete();

            if ($remove) {

                $follow = new Follow();

                $follow->user_id = get_auth_id();
                $follow->follower_id = $data['follower_id'];

                $user_info = User::find($request->follower_id);

                if (!is_null($user_info)) {
                    $user_avatar = $user_info->avatar;
                } else {
                    $user_avatar = null;
                }

                if ($follow->save()) {

                    return response([
                        "status" => 'success',
                        "name" => $data['follower_name'],
                        "id" => $data['follower_id'],
                        "avatar" => $user_avatar
                    ], 200);
                } else {

                    return response([
                        'status' => 'fail',
                        'message' => 'Follower not accepted. Connection error!'
                    ], 404);
                }


            } else {

                return response([
                    'status' => 'fail',
                    'message' => 'Error with deleting notification.'
                ], 404);
            }

        }

        return response([
            'status' => 'fail',
            'message' => 'Notification does not exists.'
        ], 422);


    }

}
