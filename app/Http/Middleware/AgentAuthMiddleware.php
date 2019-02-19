<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2019/2/18
 * Time: 10:11
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AgentAuthMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('agent/login');
            }
        }
        return $next($request);
    }
}