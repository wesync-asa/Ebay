<?php

Route::group(['prefix' => 'agent', 'namespace' => 'Agent'], function () {
    Route::get('/', function () {
        return redirect()->route('agent.login');
    });

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('agent.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('register', 'Auth\RegisterController@showRegForm')->name('agent.register');
    Route::post('register', 'Auth\RegisterController@register');

});


Route::group(['prefix' => 'agent', 'namespace' => 'Agent', 'middleware' => 'auth:agent'], function () {

    Route::resource('dashboard', 'DashboardController', array('names' => 'dashboard'));
});







