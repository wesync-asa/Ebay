<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login');


});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth:admin'], function () {

    Route::resource('dashboard', 'DashboardController', array('names' => 'dashboard'));

    Route::resource('building', 'BuildingController', array('names' => 'building'));

    Route::resource('admin', 'AdminController', array('names' => 'admin.admin'));
    Route::resource('user', 'UserController', array('names' => 'admin.user'));

});







