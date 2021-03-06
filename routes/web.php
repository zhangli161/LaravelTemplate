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

Route::any('test', 'DemoController@test');//测试

Auth::routes();//包含了所有认证需要的路由（login,register等）
//其他需要登录才能查看的路由使用中间件  ->middleware('auth');
//不通过认证的处理在app/Exceptions/Handler.php中定义unauthenticated
//如果你使用了 Laravel 自带的 LoginController 类， 就已经启用了内置的 Illuminate\Foundation\Auth\ThrottlesLogins trait 来限制用户登录失败次数。
//默认情况下，用户在几次登录失败后将在一分钟内不能登录，这种限制基于用户的用户名/邮箱地址+IP地址作为唯一键。

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user', function () {
	return new \App\Http\Resources\UserCollection(\App\Models\Template::find(1));
});