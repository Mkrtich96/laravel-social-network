<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('auth', 'AuthController',['except' => [
    'update', 'destroy'
]]);

Route::group(['middleware'=>['auth'],'namespace'=>'User'],function(){



    /**
     * User profile page
     */
    Route::get('user/{id}',['uses' => 'UserController@guestPage','as' => 'user.guest']);

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
    Route::resource('profile', 'UserController',['except' => [
        'destroy'
    ]]);


    /**
     * Search Routes
     */
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
    Route::post('/select-messages','MessageController@selectMessages');
    Route::post('/seen','MessageController@seen');
    Route::post('/send','MessageController@send');
    Route::post('/notifications','MessageController@notifications');

    /**
     * Post system
     */
    Route::resource('post','PostController');

    /**
     * Comments
     */

    Route::post('/comment-seen', 'CommentController@commentSeen');

    Route::resource('comment', 'CommentController');

});
