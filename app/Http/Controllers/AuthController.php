<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth as Auth;
use Laravel\Socialite\Facades\Socialite as Socialite;
use GuzzleHttp\Client as Client;

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
        $authUser = User::where('provider_id',$user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        $client =   new Client;
        $body   =   $client->get($user->user['url']);
        if($body->getStatusCode() === 200){
            $body = $body->getBody();
            $repo_api  =   $body->repos_url;
        }
        $data = [
            'name'  => $user->name,
            'email' => $user->email,
            'avatar'=> $user->avatar,
            'gender'=> ($provider === 'facebook') ? $user->user['gender'] : 'None',
            'provider'=> $provider,
            'api_info'=> $repo_api,
            'provider_id'=> $user->id
        ];
        return User::insertInfo($data);
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