<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('auth', 'AuthController',['except' => [
    'update', 'destroy'
]]);

/**
 * Admin profile page
 */

Route::group(['middleware' => ['admin'], 'namespace' => 'Admin'], function(){

    Route::get('/admin/notifications', [
        'uses' => 'AdminController@showNotifications',
        'as' => 'admin.notifications'
    ]);

    Route::resource('/admin','AdminController');
});


Route::group(['middleware'=>['auth'],'namespace'=>'User'],function(){

    /**
     * Connect with Stripe account
     */
    Route::get('/profile/callback/stripe','UserController@stripeConnect');

    /**
     * Disconnect Stripe account
     */
    Route::post('/profile/stripe/disconnect', [
        'uses' =>'UserController@stripeDisconnect',
        'as'=>'disconnect.stripe'
    ]);

    /**
     * User profile guest page
     */
    Route::get('user/{user}',['uses' => 'UserController@guestPage','as' => 'user.guest']);

    /**
     * Profile photo
     */
    Route::post('/update-avatar',[
        'uses'  =>  'UserController@updateProfilePhoto',
        'as'    =>  'user.avatar'
    ]);

    /**
     * Auth profile
     */
    Route::resource('profile', 'UserController',[
        'except' => ['destroy'],
        'parameters' => ['profile' => 'auth']
    ]);

    /**
     * User conversations
     */
    Route::post('/select-groups', 'ConversationsController@selectGroups');
    Route::resource('group', 'ConversationsController');

    /**
     * Search Routes
     */
    Route::get('/search-followers','SearchController@searchFollowers');

    Route::resource('/search','SearchController');

    /**
     * Follow system
     */
    Route::post('/follow','FollowController@follow');
    Route::post('/unfollow','FollowController@unfollow');
    Route::post('/cancel','FollowController@cancel');
    Route::post('/accept','FollowController@accept');

    /**
     * Gallery page
     */
    Route::post('/make-profile-photo/',[
        'uses'  =>  'GalleryController@makeProfilePhoto',
        'as'    =>  'make.profile.photo'
    ]);
    Route::resource('gallery','GalleryController');

    /**
     * Messages system
     */
    Route::post('/select-group-messages', 'MessageController@selectGroupMessages');
    Route::post('/select-messages','MessageController@selectMessages');
    Route::post('/seen','MessageController@seen');
    Route::post('/send','MessageController@send');
    Route::post('/notifications','MessageController@notifications');
    Route::post('/conversation-message', 'MessageController@conversationMessage');

    /**
     * Post system
     */
    Route::resource('post','PostController');

    /**
     * Comments
     */

    Route::post('/comment-seen', 'CommentController@commentSeen');

    Route::resource('comment', 'CommentController');

    /**
     * Stripe configurations
     */

    // user all products
    Route::resource('/products','ProductController');

    // pay product
    Route::post('/pay/{product}', [
        'uses' => 'OrderController@postPayWithStripe',
        'as' => 'pay'
    ]);

    Route::post('/store', [
        'uses' => 'OrderController@postPayWithStripe',
        'as' => 'store'
    ]);

});
