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

    public function notifications(Request $request){

        $follow_requests = array();
        $user            = User::find($request->get_id);
        $unreadNtfs      = $user->unreadNotifications;

        // Notifications
        if(count($unreadNtfs) > 0){
            foreach ($unreadNtfs as $notif) {
                $follow_requests[]    = [
                    'name'          =>  $notif->data['follower_name'],
                    'followerId'    =>  $notif->data['follower_id']
                ];
            }
            $unreadNtfs->markAsRead();

            return response([
                'status'    => 'success',
                'message'   => 'Follow request sended successfully.',
                'followers' => $follow_requests
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
                    'date'      =>  parseCreatedAt($from->created_at)
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
            if(count($data) > 0){
                return response([
                    'status'=> 'success',
                    'message'=> 'Messages selected successfully.',
                    'info'  =>  $data
                ], 200);
            }
        }else{
            /**
             * Seen messages.
             */
            $messages = Message::where('from', $request->get_id)
                                ->get()->last();

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

    public function seen(Request $request){

        $this->validate($request, ['id' => 'required|exists:users']);

        $messages = Message::where([
                                ['to',  '=',$request->id],
                                ['seen','=',2]
                            ])->get();

        if(count($messages) > 0){
            foreach ($messages as $message) {
                $message->seen = 3;
                $user_message_seen = $message->save();

                if($user_message_seen){
                    continue;
                }else{
                    return response([
                        'status' => 'fail',
                        'message'=> "'Seen don't updated. Error 404."
                    ], 404);
                }
            }
            return response([
                'status' => 'success',
                'message'=> 'Seen request sended successfully complete.',
            ], 200);
        }
    }


    public function selectMessages(StoreMessageHistory $request){

        $seen   = null;
        $data   = array();
        $messages = $this->get_chat_history($request->from, $request->to);

        if(isset($messages)){
            foreach ($messages as $message) {
                $data[] = [
                    'to'        => $message->to,
                    'from'      => $message->from,
                    'message'   => $message->message,
                    'date'      => parseCreatedAt($message->created_at)
                ];
                $seen = $message->seen;
            }

            return response([
                'status' => 'success',
                'message'=> 'Messages selected successfully.',
                'info'   => $data,
                'seen'   => $seen
            ],200);
        }
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
