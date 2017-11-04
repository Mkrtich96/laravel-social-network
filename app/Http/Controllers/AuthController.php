<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth as Auth;
use Laravel\Socialite\Facades\Socialite as Socialite;

class AuthController extends Controller
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
    public function show($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);

        if(is_null($authUser)){
            return redirect()->route('login');
        }

        Auth::login($authUser, true);

        return redirect('/profile/' . $authUser->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::find($user->id);
        if ($authUser) {
            return $authUser;
        }

        $data = [
            'name'          => $user->name,
            'email'         => $user->email,
            'avatar'        => $user->avatar,
            'gender'        => ($provider === 'facebook') ? $user->user['gender'] : 'None',
            'provider'      => $provider,
            'provider_id'   => $user->id
        ];

        $user   =   User::create($data);

        if(!is_null($user)){

            return $user;
        }

        return null;

    }

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
