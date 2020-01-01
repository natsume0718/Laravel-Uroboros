<?php

Route::get('/', function () {
    return view('top', ['user' => Auth::user()]);
})->name('top');

Route::group(['prefix' => 'auth/twitter'], function () {
    // ログインURL
    Route::get('/', 'Auth\LoginController@redirectToProvider')->name('login');
    // コールバックURL
    Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
    // ログアウトURL
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});
Route::group(['prefix' => '{user_name}', 'middleware' => ['auth', 'user.name']], function () {
    Route::get('/', 'ActivityController@index')->name('activity.index');
    Route::post('/', 'ActivityController@store')->name('activity.store');
    Route::get('/{activity}', 'ActivityController@show')->name('activity.show');
    Route::patch('/{activity}', 'ActivityController@update')->name('activity.tweet');
    Route::delete('/{activity}', 'ActivityController@destroy')->name('activity.delete');
    Route::delete('/{activity}/{id}', 'ActivityController@deleteTweet')->name('tweet.delete');
});
