<?php

namespace App\Http\Controllers\User;

use App\Post;
use App\Comment;
use App\Http\Requests\StoreComments;
use App\Http\Requests\StoreCommentSeen;
use App\Notifications\CommentNotify;
use App\Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StoreComments $request)
    {
        $commantable = true;
        $user_comment = null;

        $post = Post::find($request->post_id);
        $data = ['user_id' => get_auth('id')];

        if($request->parent_id){

            $step = explode(' ', $request->comment);
            $comment = Comment::find($request->parent_id);

            if($step[0] == $comment->user->name){

                $revise_comment = ltrim($request->comment, $comment->user->name);

                $data['comment'] = $revise_comment;
                $data['parent_id'] = $request->parent_id;

                $user_comment = $post->comments()->save(new Comment($data));

                if($user_comment){
                    $notification_data = ['user' => get_auth(), 'post' => $request->post_id];
                    $parent_notify = $user_comment->parent()->with('user')->first();
                    $parent_notify->user->notify(new CommentNotify($notification_data));
                    $commantable = false;
                }
            }else{
                $data['parent_id'] = $request->parent_id;
            }
        }

        if($commantable){
            $data['comment'] = $request->comment;
            $user_comment = $post->comments()->save(new Comment($data));
        }

        if($user_comment){

            $data = [
                'status' => 'success',
                'message'=> 'Comment send request successfully complete.',
                'comment_id' => $user_comment->id,
                'comment'  => $user_comment->comment,
                'comment_date' => $user_comment->created_at,
                'commentator' => get_auth()
            ];

            if(!is_null($user_comment->parent_id)){
                $commentable = $user_comment->parent()->with('user')->first();
                $data['comment_to'] = $commentable->user;
            }

            return response($data, 200);
        }

        return  response([
            'status'  => 'fail',
            'message' => 'Comment don\'t created, connection error!'
        ], 404);
    }

    public function commentSeen(StoreCommentSeen $request) {

        $notifications_delete = Notify::where([
                                    ['notifiable_id' ,'=', $request->notifiable_id],
                                    ['to' ,'=', $request->to],
                                    ['system' ,'=', 'comment']
                                ])->delete();

        if($notifications_delete){

            return response([
                'status' => 'success',
                'message'=> 'Notifications deleted successfully.'
            ], 200);
        }

        return response([
            'status' => 'fail',
            'message'=> 'Notification doesn\'t deleted. Connection error.'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
