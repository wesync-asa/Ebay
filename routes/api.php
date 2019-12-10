<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('getProductCount', 'ApiController@getProductCount');
Route::post('process', 'ApiController@process');

Route::get('history', 'ApiController@getHistory');
Route::post('remove', 'ApiController@removeHistory');
Route::post('category', 'ApiController@getCategory');
Route::post('download', 'ApiController@download');
Route::get('reset', 'ApiController@reset');
