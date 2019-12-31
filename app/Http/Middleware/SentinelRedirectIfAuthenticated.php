<?php

namespace App\Http\Middleware;

use App\User;
use Cartalyst\Sentinel\Users\EloquentUser;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Sentinel;

class SentinelRedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Sentinel::check()) {
            $user = Sentinel::getUser();
            $admin = Sentinel::findRoleByName('Admins');
            $domainManager = Sentinel::findRoleByName('domainManager');
            $domainSupporter = Sentinel::findRoleByName('domainSupporter');
	        if (!$user->inRole($admin) || !$user->inRole($domainManager) || $user->inRole($domainSupporter)) {
		        return redirect()->intended('admin');
	        } else {
		        return redirect()->intended('/');
	        }
        }
        return $next($request);
    }
}
