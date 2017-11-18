<?php

namespace App\Http\Controllers\User;

use App\Message;
use App\Conversations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageSend;
use App\Http\Requests\StoreMessageHistory;
use App\Http\Requests\ConversationMessageStore;

class MessageController extends Controller
{


    public function send(StoreMessageSend $request){

        $user = get_auth();

        $user_message = $user->messagesfrom()->create([
                                                    'to'=> $request->user_id,
                                                    'message' => $request->message,
                                                    'seen' => 0
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

    public function conversationMessage(ConversationMessageStore $request) {

        $seen = array();
        $auth = get_auth();
        $conversation = Conversations::find($request->conversation_id);
        $users = $conversation->users;

        foreach ($users as $user) {
            if($user->id != $auth->id){
                $seen[$user->name] = 0;
            }
        }

        $message = $conversation->messages()->create([
            'from' => $auth->id,
            'message' => $request->message,
            'seen' => json_encode($seen)
        ]);

        if($message){

            return response([
                'status' => 'success',
                'message' => 'Conversation message saved and sended successfuly',
                'date' => $message->created_at,
                'auth' => $auth
            ], 200);
        }

        return response([
            'status' => 'success',
            'message' => 'Conversation message does\'t saved! conversatonMessage()'
        ], 404);


    }


    public function selectGroupMessages(Request $request){

        $this->validate($request, ['id' => 'required|exists:conversations']);

        $auth = get_auth();
        $conversation = $auth->conversations()->find($request->id);

        $messages = $conversation->messages()->where([
                                                    ['seen' ,'LIKE', 0],
                                                    ['from','<>',$auth->id]
                                                ])->with('user')->get();

        if(count($messages) > 0) {
            $update_status = $conversation->messages()
                ->where('seen', 0)
                ->update(['seen' => 1]);

            if ($update_status) {

                return response([
                    'status' => 'success',
                    'message' => 'Messages selected successfully.',
                    'group_messages' => $messages
                ], 200);
            }

            return response([
                'status' => 'fail',
                'message' => 'Messages selected successfully. But not updated, please see (/select-group-message)',
                'group_messages' => $messages
            ], 404);
        }
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

            $update_seen = $user->messages()
                                ->where('seen',0)
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

