<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    $language = $request->input('language', 'zh');
	    if (!in_array($language, [\config('app.locale'), \config('app.fallback_locale')]))
	    {
		    $language = 'zh';
	    }
	    App::setLocale($language);
	
	    return $next($request);
    }
}
