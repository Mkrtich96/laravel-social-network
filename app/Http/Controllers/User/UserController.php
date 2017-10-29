<?php

namespace App\Http\Controllers\User;

use App\Follow;
use App\Notifications\RepliedToFollow;
use App\User;
use Auth;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
/*use GuzzleHttp\Client as Client;
use GrahamCampbell\GitHub\Facades\GitHub;*/
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UserController extends Controller
{

    protected $repositories = [];
    protected $github;

    public function __construct(GitHubManager $github)
    {
        $this->github = $github;
    }

    protected $gitAuthorization = "https://api.github.com/authorizations";

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
            'public' => 'required|',
        ];
        $this->validate($request, $rules);
        if ($request->public == "public") {
            $this->github->api('repo')->create($request->reponame, $request->description, true);
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
            $followers_list = [];
            $followers = Follow::where('user_id',$user_id)->orWhere('follower_id',$user_id)->get();

            foreach ($followers as $follower) {

                if($user_id == $follower->user_id){
                    $follow = User::find($follower->follower_id);
                    $follower_id = $follower->follower_id;
                }elseif($user_id == $follower->follower_id){
                    $follow = User::find($follower->user_id);
                    $follower_id = $follower->user_id;
                }

                $followers_list['name'][]   = $follow->name;
                $followers_list['id'][]     = $follower_id;
                $followers_list['avatar'][] = User::generate_avatar($follow);
            }


            foreach ($data->unreadNotifications as $notification) {
                $replyFollowers['message'][]    = $notification->data['follower_name'] . " send follow request";
                $replyFollowers['follower'][]   = $notification->data['follower_id'];
            }

            /**
             * Auth user Avatar
             */
            $avatar = User::generate_avatar($data);
            return view('front.user',[
                'data'          => $data,
                'avatar'        => $avatar,
                'replyFollowers'=> $replyFollowers,
                'followers'     => $followers_list
            ]);
        }


        /**
         * Providers profile
         */
        $repository = $this->github->api('user')->repositories($data->name);


        foreach ($repository as $repos) {
            $this->repositories['name'][] = $repos['name'];
            $this->repositories['url'][] = $repos['html_url'];
            $this->repositories['clone'][] = $repos['clone_url'];
        }
        switch ($data['provider']) {
            case 'github'   : $data = [
                                'name' => $data->name,
                                'avatar' => $data->avatar,
                                'provider' => $data->provider
                                ];
                return view('front.user', ['data' => $data, 'repos' => $this->repositories]);
                break;
            default: break;
        }
    }



    /**
     * User profile page
     */

    public function userPage($id){
        $user = User::find($id);
        $authId = Auth::user()->id;
        $requested = 0;
        if(is_null($user) || !is_null($user->provider)){
            return redirect('404');
        }
        if($id == $authId){
            return redirect('/');
        }
        foreach ($user->unreadNotifications as $notification) {
            if($notification->data['follower_id'] == $authId){
                $requested = 1;
                break;
            }
        };
        $follow = Follow::check_follower_or_not($id,$authId);
        if(is_null($follow)){
            $followBtn = Follow::crtFollowBtn('outline-primary follow',$id, 'Follow');
        }else{
            $followBtn = Follow::crtFollowBtn('secondary unfollow',$id, 'Unfollow');
        }
        if($requested){
            $followBtn = Follow::crtFollowBtn('secondary cancel',$id, 'Cancel Request');
        }

        return view('front.profile.user_page',[
            'user'      => $user,
            'followBtn' => $followBtn
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         *  Update profile photo
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
     * Remove the specified resource from storage.
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
