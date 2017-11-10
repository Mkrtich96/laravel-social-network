<?php

namespace App\Http\Controllers\User;

use App\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageSend;
use App\Http\Requests\StoreMessageHistory;

class MessageController extends Controller
{
    public function send(StoreMessageSend $request){

        $user = get_auth();

        $message = new Message([
                        'to'=> $request->user_id,
                        'message' => $request->message,
                        'seen' => 0
                    ]);

        $user_message = $user->messagesfrom()->save($message);

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

        $user_messages = $user->messages()->with('user')
                                            ->where('seen',0)
                                            ->get();
        /**
         * Select messages.
         */
        if(count($user_messages) > 0){

            $update_seen = $user->messages()->where('seen',0)
                ->update(['seen' => 2 ]);

            if($update_seen){

                return response([
                    'status'=> 'success',
                    'message'=> 'Messages selected successfully.',
                    'received_messages'  =>  $user_messages
                ], 200);
            }

            return response([
                'status' => 'fail',
                'message'=> "'Seen don't updated. Error 404."
            ], 404);
        }else{
            /**
             * Seen messages.
             */
            $messages = $user->messagesfrom()->get()->last();

            if(isset($messages)){
                if($messages->seen == 3){

                    $seen_update = $user->messagesfrom()->update(['seen' => 1]);

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

