<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Will allow access based on area. If none is given, the default area 'configure' is set.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  String $area     The guarded area.
     * @return mixed
     */
    public function handle($request, Closure $next, $area = 'configure') {
        if(empty($request->user()) || !$request->user()->isAdmin($area)) {
            return redirect()->route('index')->withErrors([trans('home.no_admin')]);
        }
        return $next($request);
    }
}
