<?php

Route::view('/', 'top')->name('top');

Route::group(['prefix' => 'auth/twitter'], function () {
    // ログインURL
    Route::get('/', 'Auth\LoginController@redirectToProvider')->name('login');
    // コールバックURL
    Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
    // ログアウトURL
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});
Route::group(['prefix' => '{user_name}', 'middleware' => ['auth', 'user.name']], function () {
    Route::resource('activity', 'ActivityController')->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('activity/{activity}/post', 'PostController')->only(['store', 'destroy']);
    Route::get('activity/{activity}/post/latest', 'PostController@fetchLatest')->name('post.latest');
});
