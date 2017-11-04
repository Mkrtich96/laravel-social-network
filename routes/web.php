<?php


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('auth', 'AuthController',['except' => [
    'update', 'destroy'
]]);

Route::group(['middleware'=>['auth']],function(){

    /**
     * Search Routes
     */
    Route::get('/search','User\SearchController@search');

    /**
     * Profile photo
     */
    Route::post('/updateavatar','User\UserController@updateProfilePhoto')->name('user.avatar');

    /**
     * Follow system
     */
    Route::post('/follow','User\FollowController@follow')->name('follow.request');
    Route::post('/unfollow','User\FollowController@unfollow');
    Route::post('/cancel','User\FollowController@cancel')->name('response.not.request');
    Route::post('/accept','User\FollowController@accept')->name('response.ok.request');

    /**
     * Auth profile
     */
    Route::resource('profile', 'User\UserController',['except' => [
        'destroy'
    ]]);

    /**
     * User profile page
     */
    Route::get('user/{id}','User\UserController@userPage');

    /**
     * Gallery page
     */
    Route::resource('gallery','User\GalleryController');

    /**
     * Messages system
     */
    Route::post('/selmessages','User\MessageController@select');
    Route::post('/seen','User\MessageController@seen');
    Route::post('/send','User\MessageController@send');
    Route::post('/notifications','User\MessageController@generate');

    /**
     * Post system
     */
    Route::post('/post','User\PostController@post');

    /**
     * Comments
     */
    Route::post('/comment', 'User\CommentController@comment');

});
