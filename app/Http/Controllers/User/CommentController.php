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

            $post_id        =   $request->on_post;

            $from_user      =   $request->from_user;

            $text           =   $request->text;

            $send           =   new Comment();

            $send->comment  =   $text;

            $send->post_id  =   $post_id;

            $send->from_user =   $from_user;

            $data   =   [
                'ok'    =>  1,
                'com_id' =>  $send->id
            ];

            return  ($send->save()) ? response($data, 200) : response(null, 404);

        }

    }

}
