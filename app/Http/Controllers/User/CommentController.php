<?php

namespace App\Http\Controllers\User;


use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{

    public function comment(Request $request){

        if($request->ajax()){

            $rules = [
                'from_user' =>  'required|numeric',
                'post_id'   =>  'required|numeric',
                'text'      =>  'required|regex:/^([a-zA-Z1-9\.]+)$/'
            ];

            $this->validate($request,$rules);

            $post_id        =   $request->post_id;

            $from_user      =   $request->from_user;

            $text           =   $request->text;

            $send           =   new Comment();

            $send->comment  =   $text;

            $send->on_post  =   $post_id;

            $send->from_user =   $from_user;


            return  ($send->save()) ? response(['ok' => 1, 'com_id' => $send->id], 200)
                                    : response(null, 404);

        }

    }

}
