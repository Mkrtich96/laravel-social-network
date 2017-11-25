<?php

namespace App\Http\Controllers\User;

use App\Conversations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateGet;

class ConversationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(GroupCreateGet $request)
    {
        $auth = get_auth();

        $group = $auth->conversation()->create([
            'name' => $request->name,
        ]);

        $users = $request->users;
        $users[] = $auth->id;

        if($group){

            $add_users_in_group = $group->users()->sync($users, false);

            if($add_users_in_group){

                return response([
                    'status' => 'success',
                    'message' => 'Group created and users added successfuly.',
                    'group_id' => $group->id

                ], 200);
            }

            return response([
                'status' => 'success',
                'message'=> 'Users adding in group process filed!'
            ], 404);

        }

        return response([
            'status' => 'fail',
            'message'=> 'Group conversation create error, please see database.'
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $auth = get_auth();
        $conversation = Conversations::find($id);

        if($conversation){
            $in_conversation = $conversation->users()->find($auth->id);

            if($in_conversation){

                $avatar = generate_avatar($auth);
                return view('front.profile.conversation', compact('conversation','auth', 'avatar'));
            }
        }

        return redirect()->back()->with('fail', 'Dont finding group.');
    }

    public function selectGroups(){

        $auth = get_auth();
        $conversations = $auth->conversations;

       if(count($conversations) > 0){

           return response([
               'status' => 'success',
               'message'=> 'Conversations collected successfully',
               'groups' => $conversations
           ], 200);
       }

        return response([
            'status' => 'fail',
            'message'=> 'You don\'t have groups'
        ], 404);

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
