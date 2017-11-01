<?php

namespace App\Http\Controllers\User;

use App\Notify;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{

    public function search(Request $request){

        if($request->ajax()){

            $rules = [
                'term' => 'regex:/^([1-9a-zA-Z]+)$/'
            ];

            $validate = $this->validate($request,$rules);

            if(!is_null($validate)){
                return response(null, 404);
            }

            $term = $request->term;
            $user_id = \Auth::user()->id;
            $searchResult = [];
            $requested = 0;
            $users = User::where([
                                ['name','LIKE', $term . "%"],
                                ['provider','=', null],
                                ['id','<>', $user_id],
                            ])->get();


            if(count($users) == 0){
                $searchResult[] = "Not user found";
            }else{
                foreach ($users as $user => $value) {

                    $user = User::find($value->id);
                    $follow = check_follower_or_not($user->id,$user_id);

                    $query = Notify::where('to',$user_id)->first();

                    if(!is_null($query)){
                        $requested = 1;
                    }

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
            if(isset($searchResult)){
                return response($searchResult,200);
            }
        }


    }
}
