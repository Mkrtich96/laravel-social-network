<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Post;
use App\User;
use App\Follow;
use App\Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $rules = [
            'reponame' => 'required|max:12',
            'public' => 'required',
        ];

        $this->validate($request, $rules);

        if ($request->public == "public") {
            $this->github->api('repo')
                        ->create($request->reponame, $request->description, true);
            return redirect()->back();
        } else {
            try {
                throw new Exception("Please upgrade your account for private repositories.", 404);
            } catch (Exception $e) {
                echo "Message: " . $e->getMessage();
            }
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

        $data = User::find($user_id);

        if (is_null($data)) {
            return redirect('404');
        }

        /**
         * Registered Users
         */
        if(is_null($data->provider)){

            $replyFollowers = [];

            $followers_list = $this->getFollowersList($user_id);

            $read_notifications =   $data->readnotifications;

            if(count($read_notifications) > 0){
                foreach ($read_notifications as $notification) {
                    $replyFollowers['message'][]    = $notification->data['follower_name'] . " send follow request";
                    $replyFollowers['follower'][]   = $notification->data['follower_id'];
                }
            }else{
                $replyFollowers = null;
            }

            $user_posts  =   $data->posts()->orderBy('created_at','DESC')->get();


            $posts = (count($user_posts) > 0) ? $this->createUserPostList($user_posts) : null;

            /**
             * Auth user Avatar
             */
            $avatar = $this->generate_avatar($data);

            return view('front.user',compact("data","avatar", 'replyFollowers', 'followers_list', 'posts'));
        }

        /**
         * Providers profile
         */
        $repos   = [];
        $repository = $this->github->api('user')->repositories($data->name);

        if(count($repository) > 0){
            foreach ($repository as $repos) {
                $repos['name'][]  = $repos['name'];
                $repos['url'][]   = $repos['html_url'];
                $repos['clone'][] = $repos['clone_url'];
            }
        }else{
            $repos = null;
        }

        switch ($data['provider']) {
                case 'github'   :   $data = [
                                        'name'      => $data->name,
                                        'avatar'    => $data->avatar,
                                        'provider'  => $data->provider
                                    ];
                                    return view('front.user', compact('data','repos'));
                                    break;

                default         :   break;
        }
    }



    /**
     * User profile page
     */

    public function userPage($id){

        $user       =   User::find($id);
        $authId     =   get_auth('id');
        $requested  =   0;



        dd($user->posts()->comments);


        if(is_null($user) || !is_null($user->provider)){
            return redirect('404');
        }

        if($id == $authId){
            return redirect('/');
        }

        $notifications = Notify::where('to', $authId)->first();


        if(!is_null($notifications)){

            $requested = 1;
        }

        $follow = check_follower_or_not($id,$authId);

        if(is_null($follow)){

            $followBtn = $this->crtFollowBtn('outline-primary follow',$id, 'Follow');

            $user_posts = $user->posts()->where('status',0)
                ->orderBy('created_at','DESC')
                ->get();

        }else{

            $followBtn  = $this->crtFollowBtn('secondary unfollow',$id, 'Unfollow');

            $user_posts = $user->posts()
                ->orderBy('created_at','DESC')
                ->get();

        }

        if($requested){
            $followBtn = $this->crtFollowBtn('secondary cancel',$id, 'Cancel Request');
        }


        $posts = (count($user_posts) > 0) ? $posts = $this->createUserPostList($user_posts) : null;

        $followers_list =   $this->getFollowersList($authId);

        return view('front.profile.user_page',
                    compact('user','followBtn', 'posts' , 'authId', 'followers_list')
                );

    }


    public function updateProfilePhoto(Request $request) {
        /**
         *  Update profile photo with profile
         */
        if($request->hasFile('avatar')){

            $file = $request->file('avatar');
            $ext  = $file->guessClientExtension();
            $user = Auth::user();

            if(!is_null($user->avatar)){
                unlink(storage_path('app/public/'. $user->id . '/' . $user->avatar));
            }

            $name = $request->avatar->storeAs('public/' . $user->id,'avatar.' . $ext);
            // insert avatar
            $user->avatar = basename($name);
            return ($user->save()) ? redirect()->back() : null;
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

    public function generate_avatar($data){

        $avatar = null;

        if(is_null($data->avatar)) {
            if (!$data->gender){
                $avatar = asset('images/avatars/male.gif');
            }else{
                $avatar = asset('images/avatars/female.gif');
            }
        }else{
            $avatar = asset('images/' .$data->id . '/' . $data->avatar);
        }

        return $avatar;
    }

    public function createUserPostList($user_posts) {

        $posts = [];

        foreach ($user_posts as $body) {

            $date_time  =   parseCreatedAt($body->created_at);

            $posts[] = [
                'id'    =>  $body->id,
                'text'  =>  $body->text,
                'date'  =>  $date_time
            ];
        }

        return $posts;
    }

    public function getFollowersList($user_id){

        $followers_list = [];

        $followers = Follow::where('user_id',$user_id)
                            ->orWhere('follower_id',$user_id)
                            ->get();

        if(count($followers) > 0){

            foreach ($followers as $follower) {

                if($user_id == $follower->user_id){

                    $follow = User::find($follower->follower_id);
                    $follower_id = $follower->follower_id;

                }elseif($user_id == $follower->follower_id){

                    $follow = User::find($follower->user_id);
                    $follower_id = $follower->user_id;
                }

                $followers_list['id'][]     = $follower_id;
                $followers_list['name'][]   = $follow->name;
                $followers_list['avatar'][] = $this->generate_avatar($follow);
            }

        }else{
            $followers_list = null;
        }

        return $followers_list;

    }


}
