<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('auth', 'AuthController',['except' => [
    'update', 'destroy'
]]);

Route::group(['middleware'=>['auth'],'namespace'=>'User'],function(){

    /**
     * Auth profile
     */
    Route::resource('profile', 'UserController',['except' => [
        'destroy'
    ]]);

    /**
     * Profile photo
     */
    Route::post('/update-avatar',[
        'uses'  =>  'UserController@updateProfilePhoto',
        'as'    =>  'user.avatar'
    ]);

    /**
     * User profile page
     */
    Route::get('user/{id}','UserController@userPage');

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
    Route::resource('gallery','GalleryController');
    Route::post('/make-profile-photo/',[
        'uses'  =>  'GalleryController@makeProfilePhoto',
        'as'    =>  'make.profile.photo'
    ]);

    /**
     * Messages system
     */
    Route::post('/select-messages','MessageController@selectMessages');
    Route::post('/seen','MessageController@seen');
    Route::post('/send','MessageController@send');
    Route::post('/notifications','MessageController@generate');

    /**
     * Post system
     */
    Route::resource('/post','PostController');

    /**
     * Comments
     */
    Route::resource('/comment', 'CommentController');

});
