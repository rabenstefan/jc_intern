<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;

class SemesterValid {
    /**
     * Handle an incoming request.
     * Will allow access, if the last_echo of the user is equal to the current or future semester.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(empty($request->user()) || $request->user()->last_echo < Semester::current()->id) {
            return redirect()->route('index')->withErrors([trans('home.invalid_semester')]);
        }
        return $next($request);
    }
}
