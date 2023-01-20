<?php

namespace App\Http\Middleware;

use Closure;

class LocationControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_null($request->session()->get('current_location'))) {
            return redirect()->route('site.selection');
        }
        return $next($request);
    }
}
