<?php

namespace App\Http\Controllers\User;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    public function post(Request $request){

        if($request->ajax()){

            $rules = [
                'post'  =>  'required|max:100',
                'get_id'=>  'required'
            ];

            $validate = $this->validate($request, $rules);

            if(!is_null($validate)){
                return response(null, 404);
            }

            $body   =   $request->post;

            $status =   ($request->status != "false") ? 1 : 0;

            $get_id =   $request->get_id;

            $post = new Post();

            $post->user_id  =   $get_id;

            $post->text     =   $body;

            $post->status   =   $status;

            if($post->save()){
                return response([
                    'ok'    => 1,
                    'date'  => date('M-d-Y, H:i'),
                    'post_id' => $post->id
                ], 200);
            }else{
                return response(null, 404);
            }

        }

    }
}
