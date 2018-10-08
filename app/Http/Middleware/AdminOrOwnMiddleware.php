<?php

namespace App\Http\Middleware;

use Closure;

class AdminOrOwnMiddleware
{
    /**
     * Handle an incoming request.
     * Allows either admins to access all or the current user to access own data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(empty($request->user()) || (!$request->user()->isAdmin() && $request->user()->id != $request->route()->parameter('user'))) {
            return redirect()->route('index')->withErrors([trans('home.no_admin')]);
        }
        return $next($request);
    }
}
