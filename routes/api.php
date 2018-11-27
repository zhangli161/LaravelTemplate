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
	
	Route::get('spec/getValueBySpec_id', 'Api\Admin\GoodsController@spec');//根据规格id获得规格值
	Route::get('sku/search', function (Request $request)
	{
		$q = $request->get('q');
		
		return \App\Models\GoodsSKU::query()->where('sku_name', 'like', "%$q%")
			->orWhere('id',$q)
			->orWhere('sku_no',$q)
			->paginate(null, ['id', 'sku_name as text']);
	});//根据父地区id获得地区
	
});



//小程序api
Route::group(['namespace' => 'api'], function () {
	Route::post('/login', 'UserController@login');//登录
	
});
//Route::group(['middleware' => 'auth:api', 'namespace' => 'api'], function() {
Route::group(['middleware' => 'test', 'namespace' => 'api'], function() {

	Route::get('/putUserInfo', 'UserController@putUserInfo');//同步微信信息
	
	Route::get('/passport', 'UserController@passport');//获得用户信息
	Route::get('/banner', 'BannerController@getList');//轮播图
	
	Route::post('/sendVertifyCode', 'UserController@sendVertifyCode');//发送验证码
	
	Route::get('/message', 'MessageController@getList');//消息
	Route::post('/message', 'MessageController@getList');//消息
	
	Route::get('/goods', 'GoodsController@getList');//消息
	Route::get('/goods/getByid', 'GoodsController@getById');//消息
});

//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
//	Route::get('/user', function( Request $request ){
//		return $request->user();
//	});
//});