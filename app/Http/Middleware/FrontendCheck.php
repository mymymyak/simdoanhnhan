<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
class FrontendCheck
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
        $domain = $request->server('HTTP_HOST');
        $request->request->add(['currentDomain' => $domain]);
        return $next($request);
    }
}
