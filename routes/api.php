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

Route::post('/login', 'Api\UserController@login');//登录


Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
//Route::group(['middleware' => 'test', 'namespace' => 'api'], function () {

    Route::post('/putUserInfo', 'UserController@putUserInfo');//同步微信信息

    Route::get('/passport', 'UserController@passport');//获得用户信息
    Route::get('/banner', 'BannerController@getList');//轮播图

    Route::post('/sendVertifyCode', 'UserController@sendVertifyCode');//发送验证码

    Route::get('/message', 'MessageController@getList');//消息
//	Route::post('/message', 'MessageController@getList');//消息

    Route::get('/goods', 'GoodsController@getList');//全部商品
    Route::get('/goods/getById', 'GoodsController@getById');//商品详情
    Route::get('/goods/search', 'GoodsController@search');//搜索
    Route::post('/goods/addtocart', 'GoodsController@addToCart');//添加至购物车
    Route::get('/cart', 'CartController@myCart');//我的购物车

    Route::get('/coupon', 'CouponController@getList');//所有优惠券
    Route::post('/coupon/buy', 'CouponController@buy');//购买
    Route::get('/coupon/my', 'CouponController@myCoupons');//我的优惠券

    Route::get('/favorite/my/goods', 'FavoriteController@myFavoriteSPU');//我的收藏
    Route::post('/favorite/add', 'FavoriteController@add');//收藏

    Route::get('/footprint', 'GoodsController@footprint');//足迹

    Route::get('/credit/record', 'UserController@credit_record');//积分记录

    Route::post('/apply/agent', 'AgentController@apply');//申请代理
    Route::post('/apply/create_admin', 'AgentController@create_admin');//提交管理员信息

    Route::post('/bind/agent', 'UserController@bindAgent');//申请代理

    Route::post('/order/settlement', 'OrderController@settlement');//订单结算
    Route::post('/order/create', 'OrderController@create');//生成订单
    Route::get('/order/my', 'OrderController@my');//我的订单
    Route::get('/order/getById', 'OrderController@getById');//根据id获取订单
    Route::any('/order/pay', 'OrderController@pay');//微信统一下单订单
    Route::any('/order/refund', 'OrderController@refund');//微信统一下单订单

    Route::get('/article/getByCategory', 'ArticleController@getByCategory');//根据分类获取列表
    Route::get('/article/getOneByCategory', 'ArticleController@getOneByCategory');//根据分类获取单个
    Route::get('/article/getById', 'ArticleController@getById');//根据分类获取单个

    Route::get('/user/orders/count', 'OrderController@getCount');//用户地址

    Route::get('/user/addresses', 'UserAddressController@my');//用户地址
    Route::post('/user/address/edit', 'UserAddressController@edit');//编辑地址

    Route::post('/user/info', 'UserController@editInfo');//更改用户信息
});
Route::any('payment/notify', 'OrderController@notify');//微信支付回调


//Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
//	Route::get('/user', function( Request $request ){
//		return $request->user();
//	});
//});