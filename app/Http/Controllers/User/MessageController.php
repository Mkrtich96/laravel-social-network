<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\StoreMessageHistory;
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
                        'message'=> 'Message sended successfully complete.',
                        'date'  => $user_message->created_at
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

    public function notifications(){

        $user = get_auth();

        $notifications = $user->unreadNotifications;

        // Notifications
        if(count($notifications) > 0){

            $notifications->markAsRead();

            return response([
                'status' => 'success',
                'message' => 'Notify request sended successfully.',
                'notifications' => $notifications
            ], 200);
        }

        $user_messages = $user->messages()->where('seen',0)
                                            ->get();

        /**
         * Select messages.
         */
        if(count($user_messages) > 0){

            $data = array();

            foreach ($user_messages as $message) {

                $from = User::find($message->from);
                $data[] = [
                    'message'   =>  $message->message,
                    'id'        =>  $from->id,
                    'name'      =>  $from->name,
                    'avatar'    =>  $from->avatar,
                    'date'      =>  $from->created_at
                ];
                $update_seen = $user->messages()->where('seen',0)
                                                    ->update(['seen' => 2 ]);
                if($update_seen){
                    continue;
                }else{
                    return response([
                                'status' => 'fail',
                                'message'=> "'Seen don't updated. Error 404."
                            ], 404);
                }
            }

            return response([
                'status'=> 'success',
                'message'=> 'Messages selected successfully.',
                'info'  =>  $data
            ], 200);

        }else{
            /**
             * Seen messages.
             */
            $messages = Message::where('from',$user->id)->get()->last();

            if(!is_null($messages)){
                if($messages->seen == 3){

                    $messages->seen = 1;
                    $seen_update = $messages->save();

                    if($seen_update){

                        return response([
                            'status' => 'success',
                            'message'=> 'Message seen successfully.',
                            'seen'   =>  true
                        ],200);
                    }
                    return response([
                        'status' => 'fail',
                        'message'=> 'Message seen request error.'
                    ], 404);
                }
            }
        }
    }

    public function seen(){

        $user = get_auth();

        $messages = $user->messages()->where('seen',2)->get();

        if(count($messages) > 0){
            $messages_seen_update = $user->messages()->where('seen',2)
                                            ->update(['seen' => 3]);
            if($messages_seen_update)
            return response([
                'status' => 'success',
                'message'=> 'Seen request sended successfully complete.',
            ], 200);
        }
    }


    public function selectMessages(StoreMessageHistory $request){

        $from = get_auth();

        $messages = $this->get_chat_history($from->id, $request->to);

        if(isset($messages)){
            return response([
                'status' => 'success',
                'message'=> 'Messages selected successfully.',
                'messages' => $messages,
            ],200);
        }
        return response([
            'status' => 'success',
            'message'=> 'Messages not found.'
        ], 200);

    }


    public function get_chat_history($from,$to){

        $get_message_history =  Message::where(function($query) use ($from, $to) {
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

        if(count($get_message_history) > 0){

            return $get_message_history;
        }else{
            return null;
        }

    }
}
