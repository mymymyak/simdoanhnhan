<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class SentinelAdminUser {

	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle ($request, Closure $next) {
		$user            = Sentinel::getUser();
		$admin           = Sentinel::findRoleBySlug('superadmin');
		$domainManager   = Sentinel::findRoleBySlug('domainManager');
		$domainSupporter = Sentinel::findRoleBySlug('domainSupporter');
		if (!$user->inRole($admin)) {
			if ($user->inRole($domainManager) || $user->inRole($domainSupporter)) {
				if (empty($_COOKIE['domain_setting'])) {
					setcookie('domain_setting', $user->domain, time() + (86400 * 30), "/");
				}
			} else {
				return redirect('login');
			}
		}
		return $next($request);
	}
}
