<?php

namespace App\Http\Controllers\User;

use App\Message;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function send(Request $request,$id){
        $from = Auth::user()->id;
        if($request->ajax()){
            $send           = new Message();
            $send->from     = $from;
            $send->to       = $id;
            $send->message  = strip_tags($request->message);
            $send->seen     = 0;
            return ($send->save()) ? ['ok' => 1,'date' => date('h:i M-D-y')] : 0;
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

    public function generate($id){
        $infoOk         = false;
        $replyFollowers = [];
        $messages       = Message::where('to', $id)->where('seen', 0)->get();
        $notifications  = User::find($id);
        $unreadNtfs = $notifications->unreadNotifications;
        dd($notifications->readNotifications);
        // Notifications
        if(count($unreadNtfs) > 0){
            foreach ($unreadNtfs as $notif) {
                $replyFollowers['message'][]    = [
                    'name'          =>  $notif->data['follower_name'],
                    'followerId'    =>  $notif->data['follower_id']
                ];
            }
            $unreadNtfs->markAsRead();
            $infoOk = true;
        }

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
                        'notif'     =>  ($infoOk) ? $replyFollowers : null,
                ];
                $message->seen = 2;
                $message->save();
            }
            return $data;
        }else{
            // Seen Messages
            $messages = Message::where('from', $id)->get()->last();
            if(!is_null($messages)){
                if($messages->seen == 3){
                    $messages->seen = 1;
                    $messages->save();
                    return ($infoOk) ? $replyFollowers : 1;
                }
            }
        }
        return ($infoOk) ? $replyFollowers : 0;
    }

    public function seen(Request $request){
        if($request->ajax()){
            $messages = Message::where('to', $request->id)->where('seen', 2)->get();
            if(count($messages) > 0){
                foreach ($messages as $message) {
                    $message->seen = 3;
                    $message->save();
                }
            }
        }
    }


    public function select(Request $request){
        if($request->ajax()){
            $data   = [];
            $seen   = null;
            $from   = $request->from;
            $to     = $request->to;
            $messages = Message::get_chat_history($from, $to);
            foreach ($messages as $message) {
                $data[] = [
                    'from'      => $message->from,
                    'to'        => $message->to,
                    'message'   => strip_tags($message->message),
                    'date'      => $message->created_at,
                ];
                $seen = $message->seen;
            }
            return ['item' => $data,'seen' => $seen];
        }
    }
}
