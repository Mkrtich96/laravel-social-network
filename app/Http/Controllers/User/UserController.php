<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Follow;
use App\Notify;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\IndexGuest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfilePhoto;
use GrahamCampbell\GitHub\GitHubManager;


class UserController extends Controller
{

    protected $github;

    public function __construct(GitHubManager $github)
    {
        $this->github = $github;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('home');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        if ($request->public == "public") {

            $user_create_repo = $this->github->api('repo')
                                            ->create($request->reponame, $request->description, true);
            if($user_create_repo){
                return redirect()->back();
            }else{
                return redirect()->back()
                        ->with('fail','Connection error. Doesn\'t created respository');
            }
        } else {
            return redirect()->back()
                    ->with('fail', "Please upgrade your account for private repositories.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     */
    public function show($user_id)
    {

        $auth = User::find($user_id);

        /**
         * Registered Users
         */
        if(is_null($auth->provider)){

            // Auth user followers
            $followers_list = $this->getFollowersList($auth->id);

            // Auth user notifications
            $notifications =   $auth->readnotifications;

            // Auth user posts
            $posts  =   $this->generatePostStatus($auth,true);

            // Auth user avatar
            $user_avatar = generate_avatar($auth);

            return view('front.user',compact(
                     'auth',
                          'user_avatar',
                             'notifications',
                             'followers_list',
                             'posts'));
        }

        /**
         * Providers profile
         */
        $user_repositories = $this->github->api('user')->repositories($auth->name);

        if(count($user_repositories) > 0){

            $repositories = $user_repositories;
        }else{
            $repositories = null;
        }

        switch ($auth['provider']) {
                case 'github':  return view('front.user',
                                        compact('auth','repositories'));
                                break;
                default:   break;
        }
    }



    /**
     * User profile page
     */

    public function guestPage(IndexGuest $request, $id){

        $user = User::find($request->id);
        $auth = get_auth();
        $post_status = false;

        $consider_follow = $user->followers()->where('user_id',$auth->id)
                                                ->orWhere('follower_id',$auth->id)
                                                ->first();


        if(!is_null($user->provider) || $id == $auth->id){

            return redirect('/');
        }

        $notifications = Notify::where('to', $auth->id)->first();

        if(is_null($consider_follow)){

            $followButton = $this->crtFollowBtn('outline-primary follow',$id, 'Follow');
        }elseif(!is_null($notifications)){

            $followButton = $this->crtFollowBtn('secondary cancel-follow',$id, 'Cancel Request');
        }else{

            $followButton = $this->crtFollowBtn('secondary unfollow',$id, 'Unfollow');
            $post_status = true;
        }


        $posts = $this->generatePostStatus($user, $post_status);

        $user_avatar = generate_avatar($user);
        $followers_list =   $this->getFollowersList($auth->id);


        return view('front.profile.user_page',
                    compact('user','user_avatar','user_comments','followButton', 'posts', 'auth', 'followers_list')
                );
    }

    /**
     *  Update profile photo in profile page NOT FROM GALLERY
     */

    public function updateProfilePhoto(UserProfilePhoto $request) {

        $file = $request->file('avatar');
        $ext  = $file->guessClientExtension();
        $user = get_auth();

        if(!is_null($user->avatar)){

            unlink(storage_path('app/public/'. $user->id . '/' . $user->avatar));
        }

        $name = $request->avatar->storeAs('public/' . $user->id,'avatar.' . $ext);

        $user->avatar = basename($name);
        $user_update_avatar = $user->save();

        if($user_update_avatar){
            return redirect()->back();
        }else{
            return redirect()->back()->with('fail','Error with updating profile photo!');
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }


    /**
     * @param $class
     * @param $data_id
     * @param $text
     * @return string
     *
     *
     * Custom created methods!!
     */

    public function crtFollowBtn($class, $data_id, $text){

        $button =   "<button class='btn btn-". $class ."' data-id='". $data_id ."'>"
                        . $text .
                    "</button>";

        return $button;
    }

    public function generatePostStatus($user, $status = false) {

        $user_posts = null;

        if($status === true){

            $user_posts = $user->posts()->with('comments.user')
                                        ->orderBy('created_at','DESC')
                                        ->get();
        }else{
            $user_posts = $user->posts()->where('status',0)
                                ->with('comments.user')
                                ->orderBy('created_at','DESC')
                                ->get();
        }

        if(count($user_posts) > 0){

            return $user_posts;
        }
        return null;
    }

    public function getFollowersList($user_id)
    {

        $followers = Follow::where('user_id', $user_id)
            ->orWhere('follower_id', $user_id)
            ->get();

        if (count($followers) > 0) {

            return $followers;
        }

        return null;
    }
}
