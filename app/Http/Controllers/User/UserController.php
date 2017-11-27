<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Follow;
use App\Notify;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfilePhoto;
use GrahamCampbell\GitHub\GitHubManager;


class UserController extends Controller
{

    protected $github;

    protected $disconnect_url = 'https://connect.stripe.com/oauth/deauthorize';

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $auth)
    {

        /**
         * Registered Users
         */
        if(is_null($auth->provider)){

            // Auth user followers
            $followers_list = $this->getFollowersList($auth->id);

            // Auth user notifications
            $notifications = $auth->readnotifications;

            // Auth user posts
            $posts = $this->generatePostStatus($auth,true);

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

    public function guestPage(User $user){

        $auth = get_auth();
        $post_status = false;

        $consider_follow = $user->followers()->where('user_id',$auth->id)
                                                ->orWhere('follower_id',$auth->id)
                                                ->first();


        if(!is_null($user->provider) || $user->id == $auth->id || $user->admin){

            return redirect('/');
        }

        $notifications = Notify::where('to', $auth->id)->first();

        if(is_null($consider_follow)){

            $followButton = $this->crtFollowBtn('outline-primary follow',$user->id, 'Follow');
        }elseif(!is_null($notifications)){

            $followButton = $this->crtFollowBtn('secondary cancel-follow',$user->id, 'Cancel Request');
        }else{

            $followButton = $this->crtFollowBtn('secondary unfollow',$user->id, 'Unfollow');
            $post_status = true;
        }

        $posts = $this->generatePostStatus($user, $post_status);
        $user_avatar = generate_avatar($user);
        $followers_list = $this->getFollowersList($auth->id);

        $products = $user->products()->where('status', true)->get();

        if(count($products) === 0){
            $products = null;
        }

        return view('front.profile.user_page',
                    compact('user',
                                 'user_avatar',
                                    'user_comments',
                                    'followButton',
                                    'products',
                                    'posts',
                                    'auth',
                                    'followers_list')
                );
    }

    public function guestProducts(User $user){

        $products = $user->products()->where('status', 1)->get();

        $products = $products->count() ? $products : null;

        return view('front.profile.user_page_products', compact('products'));
    }


    /**
     * Connect user stripe account to platform
     */

    public function stripeConnect(Request $request)
    {
        if (isset($request->code)) {

            $token_request_body = [
                'client_secret' => env('STRIPE_SECRET'),
                'code' => $request->code,
                'grant_type' => 'authorization_code',
            ];

            $response = Curl::to(env('STRIPE_TOKEN_URI'))
                                ->withData($token_request_body)
                                ->asJson(true)
                                ->post();

            if($response){

                $auth = get_auth();
                $auth->stripe_account_id = $response['stripe_user_id'];

                if($auth->save()){

                    return redirect('/products')
                                ->with('success','You are successfully connected!');
                }
            }
        }

        return redirect()
                ->back()
                ->with('error', 'Please try again later. Request failed.');
    }

    public function stripeDisconnect()
    {

        $auth = get_auth();

        $response = Curl::to($this->disconnect_url)
                        ->withHeader("Authorization: Bearer " . env('STRIPE_SECRET'))
                        ->withData([
                            'client_id' => env('STRIPE_CLIENT_ID'),
                            'stripe_user_id' => $auth->stripe_account_id
                        ])->post();

        if ($response) {

            $update_disconnect = $auth->update(['stripe_account_id' => null]);

            if ($update_disconnect) {

                return redirect('/')
                            ->with('success', 'You deauthorized from stripe account.');
            }

            return redirect('/')
                        ->with('error', 'Your stripe account dissconneced, but not saved in server. Please try again later.');
        }

        return redirect()
            ->back()
            ->with('error', 'An error has occured. Please try again later.');
    }


    /**
     *  Update profile photo in profile page NOT FROM GALLERY
     */

    public function updateProfilePhoto(UserProfilePhoto $request) {

        $user = get_auth();
        $file = $request->file('avatar');
        $ext  = $file->guessClientExtension();

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
