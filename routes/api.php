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
//管理后台api接口
Route::group(['prefix' => 'admin'], function(){
	Route::get('category', 'Api\CategoryController@category');//类别
	
	Route::get('region/getByParentid', 'Api\RegionController@getByParentid');//根据父地区id获得地区
	Route::get('regions', 'Api\RegionController@regions');//地区模糊搜索
});



//小程序api
Route::group(['namespace' => 'api'], function () {
	Route::post('/login', 'UserController@login');//登录
	
});
Route::group(['middleware' => 'auth:api', 'namespace' => 'api'], function() {
	Route::get('/putUserInfo', 'UserController@putUserInfo');//同步微信信息
	
	Route::get('/test/passport', 'UserController@passport');//获得用户信息
	Route::get('/banner', 'BannerController@getList');
});

//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
//	Route::get('/user', function( Request $request ){
//		return $request->user();
//	});
//});