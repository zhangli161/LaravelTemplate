<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
	$router->resource('api', ApiController::class);
	$router->resource('users', UserController::class);
	$router->resource('regions', NativePlaceRegionController::class);
	$router->resource('user_address', UserAddressController::class);
	$router->resource('banner', BannerController::class);
	$router->resource('category', CategoryController::class);
	$router->resource('message', MessageContentController::class);
	$router->resource('message_source', MessageSourceController::class);
	$router->resource('goods', GoodsController::class);
	$router->resource('goods_sku', GoodsSKUController::class);
	$router->resource('postage', PostageController::class);
	$router->resource('spec', GoodsSpecController::class);
	$router->resource('benefit', GoodsBenefitController::class);
	$router->resource('richtext', RichTextController::class);
	$router->resource('coupon', CouponController::class);
	$router->resource('order', OrderController::class);
	$router->resource('article', ArticleController::class);
	
});
