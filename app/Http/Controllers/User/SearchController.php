<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{

    public function search(Request $request){
        $term = $request->term;
        $user_id = \Auth::user()->id;
        $searchResult = [];
        $requested = 0;
        $users = User::where('name','LIKE', $term . '%')
                        ->where('provider',null)
                        ->where('id','<>',$user_id)
                        ->get();
        if(count($users) == 0){
            $searchResult[] = "Not user found";
        }else{
            foreach ($users as $user => $value) {

                $user = User::find($value->id);
                $follow = check_follower_or_not($user->id,$user_id);

                foreach ($user->unreadNotifications as $notification) {
                    if($notification->data['follower_id'] == $user_id){
                        $requested = 1;
                        break;
                    }
                };
                /**
                 * Avatar
                 */

                if(is_null($value->avatar)){
                    $avatar = ($value->gender) ? asset('images/avatars/female.gif') : asset('images/avatars/male.gif');
                }else{
                    $avatar = asset('images/' . $value->id . '/' . $value->avatar);
                }
                $searchResult[] = [
                    'value'     =>  $value->name,
                    'id'        =>  $value->id,
                    'follow'    =>  (is_null($follow)) ? 0 : 1,
                    'avatar'    =>  $avatar,
                    'requested' =>  $requested
                ];
                $requested = 0;
            }
        }
        return $searchResult;
    }
}
