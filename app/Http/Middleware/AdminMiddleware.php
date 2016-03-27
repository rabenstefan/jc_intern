<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(empty($request->user()) || !$request->user()->isAdmin()) {
            return redirect()->route('index')->withErrors([trans('home.no_admin')]);
        }
        return $next($request);
    }
}
