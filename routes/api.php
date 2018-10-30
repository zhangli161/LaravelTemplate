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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
//	Route::get('/user', function( Request $request ){
//		return $request->user();
//	});
//});

Route::get('region/getByParentid', 'Api\RegionController@getByParentid');//测试
Route::get('regions', 'Api\RegionController@regions');//测试

Route::group(['namespace' => 'api'], function () {
	Route::post('/login', 'UserController@login');
});
Route::group(['middleware' => 'auth:api', 'namespace' => 'api'], function() {
	Route::get('/test/passport', 'UserController@passport');
});