<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App;
use Session;

class LanguageControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Session::has('current_locale')){
            Session::put('current_locale','en');
            App::setLocale('en');
        }

        if(Session::has('current_locale') && Session::get('current_locale') != App::getLocale()){
            App::setLocale(Session::get('current_locale'));
        }
        return $next($request);
    }
}
