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
    Route::post('/updateavatar',['uses'=>'User\UserController@updateProfilePhoto','as'=>'user.avatar']);

    /**
     * Follow system
     */
    Route::post('/follow/{user_id}','User\FollowController@follow')->name('follow.request');
    Route::post('/unfollow/{follower_id}','User\FollowController@unfollow');
    Route::post('/cancel/{follower_id}','User\FollowController@cancel')->name('response.not.request');
    Route::post('/accept/{follower_id}','User\FollowController@accept')->name('response.ok.request');

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
    Route::post('selmessages/','User\MessageController@select');
    Route::post('seen/','User\MessageController@seen');
    Route::post('send/{id}','User\MessageController@send');
    Route::post('generate_message/{id}','User\MessageController@generate');
});
