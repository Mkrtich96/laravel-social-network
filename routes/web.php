<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('auth', 'AuthController',['except' => [
    'update', 'destroy'
]]);

Route::group(['middleware'=>['auth'],'namespace'=>'User'],function(){

    /**
     * Search Routes
     */
    Route::get('/search','SearchController@search');

    /**
     * Profile photo
     */
    Route::post('/update-avatar',[
        'uses'  =>  'UserController@updateProfilePhoto',
        'as'    =>  'user.avatar'
    ]);

    /**
     * Follow system
     */
    Route::post('/follow','FollowController@follow');
    Route::post('/unfollow','FollowController@unfollow');
    Route::post('/cancel','FollowController@cancel');
    Route::post('/accept','FollowController@accept');

    /**
     * Auth profile
     */
    Route::resource('profile', 'UserController',['except' => [
        'destroy'
    ]]);

    /**
     * User profile page
     */
    Route::get('user/{id}','UserController@userPage');

    /**
     * Gallery page
     */
    Route::resource('gallery','GalleryController');
    Route::post('/make-profile-photo',[
        'uses'  =>  'GalleryController@makeProfilePhoto',
        'as'    =>  'make.profile.photo'
    ]);

    /**
     * Messages system
     */
    Route::post('/selmessages','MessageController@select');
    Route::post('/seen','MessageController@seen');
    Route::post('/send','MessageController@send');
    Route::post('/notifications','MessageController@generate');

    /**
     * Post system
     */
    Route::post('/post','PostController@post');

    /**
     * Comments
     */
    Route::post('/comment', 'CommentController@comment');

});
