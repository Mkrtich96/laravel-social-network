<?php

namespace App\Http\Controllers\User;


use App\User;
use App\Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchUsersGet;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index(SearchUsersGet $request)
    {

        $auth_id = get_auth('id');
        $searchResult = array();

        $users = User::where([
                            ['name', 'LIKE', $request->term . "%"],
                            ['provider', '=', null],
                            ['id', '<>', $auth_id]
                        ])->get();


        if (count($users) == 0) {
            $searchResult[] = "Users not found.";
        } else {
            $notify = Notify::where('to', $auth_id)->first();

            foreach ($users as $user) {

                if (!is_null($notify)) {
                    $follow = 2;
                }else{
                    $follow = check_follower_or_not($user->id, $auth_id);
                    $follow = (is_null($follow)) ? 0 : 1;
                }

                $user_avatar = generate_avatar($user);

                $searchResult[] = [
                    'id'     => $user->id,
                    'value'  => $user->name,
                    'follow' => $follow,
                    'avatar' => $user_avatar
                ];
            }
        }

        return response($searchResult, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
