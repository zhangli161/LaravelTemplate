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
	
});
