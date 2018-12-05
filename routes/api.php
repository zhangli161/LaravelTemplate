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

Route::get('/test', 'DemoController@test')->middleware('test');//测试接口

//管理后台api接口
Route::group(['prefix' => 'admin'], function () {
	Route::get('category', 'Api\CategoryController@category');//类别
	
	Route::get('region/getByParentid', 'Api\RegionController@getByParentid');//根据父地区id获得地区
	Route::get('regions', 'Api\RegionController@regions');//地区模糊搜索
	
	Route::get('spec/getValueBySpec_id', 'Api\Admin\GoodsController@spec');//根据规格id获得规格值
	Route::get('sku/search', function (Request $request) {
		$q = $request->get('q');
		
		return \App\Models\GoodsSKU::query()->where('sku_name', 'like', "%$q%")
			->orWhere('id', $q)
			->orWhere('sku_no', $q)
			->paginate(null, ['id', 'sku_name as text']);
	});//根据父地区id获得地区
	
});


//小程序api
Route::group(['namespace' => 'api'], function () {
	Route::post('/login', 'UserController@login');//登录
	
});
//Route::group(['middleware' => 'auth:api', 'namespace' => 'api'], function() {
Route::group(['middleware' => 'test', 'namespace' => 'api'], function () {
	
	Route::post('/putUserInfo', 'UserController@putUserInfo');//同步微信信息
	
	Route::get('/passport', 'UserController@passport');//获得用户信息
	Route::get('/banner', 'BannerController@getList');//轮播图
	
	Route::post('/sendVertifyCode', 'UserController@sendVertifyCode');//发送验证码
	
	Route::get('/message', 'MessageController@getList');//消息
//	Route::post('/message', 'MessageController@getList');//消息
	
	Route::get('/goods', 'GoodsController@getList');//全部商品
	Route::get('/goods/getById', 'GoodsController@getById');//商品详情
	Route::get('/goods/search', 'GoodsController@search');//搜索
	Route::post('/goods/addtocart', 'GoodsController@addToCart');//添加至购物车额
	
	Route::get('/coupon', 'CouponController@getList');//所有优惠券
	Route::post('/coupon/buy', 'CouponController@buy');//购买
	Route::get('/coupon/my', 'CouponController@myCoupons');//我的优惠券
	
	Route::get('/favorite/my/goods', 'FavoriteController@myFavoriteSPU');//我的收藏
	Route::post('/favorite/add', 'FavoriteController@add');//收藏
	
	Route::get('/footprint', 'GoodsController@footprint');//足迹
	
	Route::get('/credit/record', 'UserController@credit_record');//积分记录
	
	Route::get('/order/settlement', 'OrderController@settlement');//订单结算
	Route::get('/order/create', 'OrderController@create');//生成订单
	Route::get('/order/my', 'OrderController@my');//我的订单
	Route::get('/order/getById', 'OrderController@getById');//我的订单
	
	
});

//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
//	Route::get('/user', function( Request $request ){
//		return $request->user();
//	});
//});