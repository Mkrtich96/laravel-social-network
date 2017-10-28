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

    public function generate($id){
        $messages = Message::where('to', $id)->where('seen', 0)->get();
        if(count($messages) > 0){
            $data = [];
            foreach ($messages as $message) {
                $from = User::find($message->from);
                $data['info'] = [
                        'message'   =>  strip_tags($message->message),
                        'id'        =>  $from->id,
                        'name'      =>  $from->name,
                        'avatar'    =>  $from->avatar,
                        'date'      =>  date('h:i M-D-y')
                ];
                $message->seen = 2;
                $message->save();
            }
            return $data;
        }else{
            $messages = Message::where('from', $id)->get()->last();
            if($messages->seen == 3){
                $messages->seen = 1;
                return ($messages->save()) ? 1 : 0;
            }
            return 0;
        }
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
