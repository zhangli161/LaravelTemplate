<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/27
 * Time: 9:22
 */

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class TestApi
{
	public function handle($request, Closure $next, $guard = null){
		Auth::login(User::find(1));
		return $next($request);
	}
}