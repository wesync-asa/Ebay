<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/ebay', 'HomeController@testEbay')->name('testEbay');
Route::get('test', function() {
    $id = request()->id;
    // return response()->json(['files' => asset("downloads/".$id."/".$id.".csv")]);
    $idx = 0;
    $zipArray = array();
    while(file_exists(public_path("downloads/".$id."/".$id."_".$idx.".zip"))){
        array_push($zipArray, asset("downloads/".$id."/".$id."_".$idx.".zip"));
        $idx++;
    }
    return response()->json(['files' => $zipArray]);
    dd($id);
});
