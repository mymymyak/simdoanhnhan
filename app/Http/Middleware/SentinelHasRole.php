<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
class SentinelHasRole
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
        $user = Sentinel::getUser();
        $admin = Sentinel::findRoleByName('Admins');
        $currentRouteName = Route::currentRouteName();
        if (!$admin && !$user->hasAccess($currentRouteName)) {
            return $this->forbiddenResponse();
        }
        return $next($request);
    }
    // OPTIONAL OVERRIDE
    public function forbiddenResponse()
    {
        return redirect()->route('admin_dashboard');
    }
}
