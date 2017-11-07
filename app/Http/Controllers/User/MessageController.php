<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageSend;

class MessageController extends Controller
{
    public function send(StoreMessageSend $request){

        $auth_id = get_auth('id');

        $user_message = Message::create([
            'from'  =>  $auth_id,
            'to'    =>  $request->user_id,
            'message' =>    $request->message,
            'seen'  =>  0
        ]);

        if($user_message){
            return response([
                        'status' => 'success',
                        'date'  => parseCreatedAt($user_message->created_at)
                    ],200);
        }

        return response([
            'status' => 'fail',
            'message'=> 'Send message failed! Error 404.'
        ],404);
    }


    /**
     * @param $id
     * @return array|int
     *
     * This function for real time message response...
     * ...OR user notification response
     * ...OR seen message response
     *
     */

    public function generate(Request $request){

        $rules = [
            'get_id' => 'required',
            'message' => 'max:200'
        ];
        $validate = $this->validate($request, $rules);

        if(!is_null($validate)){

            return response(null, 404);
        }

        $get_id = $request->get_id;

        $replyFollowers = [];

        $notifications  = User::find($get_id);
        $unreadNtfs = $notifications->unreadNotifications;

        // Notifications
        if(count($unreadNtfs) > 0){
            foreach ($unreadNtfs as $notif) {
                $replyFollowers['message'][]    = [
                    'name'          =>  $notif->data['follower_name'],
                    'followerId'    =>  $notif->data['follower_id']
                ];
            }
            $unreadNtfs->markAsRead();

            return response($replyFollowers, 200);
        }

        $messages       = Message::where([
                                        ['to','=',$get_id],
                                        ['seen','=',0]
                                    ])->get();

        // Messages
        if(count($messages) > 0){
            $data = [];
            foreach ($messages as $message) {
                $from = User::find($message->from);
                $data['info'] = [
                    'message'   =>  strip_tags($message->message),
                    'id'        =>  $from->id,
                    'name'      =>  $from->name,
                    'avatar'    =>  $from->avatar,
                    'date'      =>  date('h:i M-D-y'),
                ];
                $message->seen = 2;
                $message->save();
            }
            if(count($data) > 0){

                return response($data, 200);
            }

        }else{
            // Seen Messages
            $messages = Message::where('from', $get_id)->get()->last();
            if(!is_null($messages)){
                if($messages->seen == 3){
                    $messages->seen = 1;
                    if($messages->save()){

                        return response(['ok' => 1],200);
                    }
                    return response(null, 404);
                }
            }
        }


    }

    public function seen(Request $request){

        $messages = Message::where([
                                ['to',  '=',$request->id],
                                ['seen','=',2]
                            ])->get();

        if(count($messages) > 0){
            foreach ($messages as $message) {
                $message->seen = 3;
                $message->save();
            }
        }

    }


    public function selectMessages(Request $request){

        $data   = array();
        $seen   = null;
        $from   = $request->from;
        $to     = $request->to;
        $messages = $this->get_chat_history($from, $to);
        foreach ($messages as $message) {
            $data[] = [
                'from'      => $message->from,
                'to'        => $message->to,
                'message'   => strip_tags($message->message),
                'date'      => parseCreatedAt($message->created_at)
            ];
            $seen = $message->seen;
        }
        return response(['item' => $data,'seen' => $seen],200);

    }


    public function get_chat_history($from,$to){

        $get =  Message::where(function($query) use ($from, $to) {
            $query->where([
                        ['to','=',$to],
                        ['from','=',$from]
                    ]);
        })->orWhere(function($query) use ($from, $to) {
            $query->where([
                        ['to','=',$from],
                        ['from','=',$to]
                    ]);
        })->get();

        return $get;
    }
}
