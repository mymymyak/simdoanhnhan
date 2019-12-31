<?php

namespace App\Http\Response\ResponseCache;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\BaseCacheProfile;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;
use Symfony\Component\HttpFoundation\Response;

class AppCacheProfile extends BaseCacheProfile {

	public function shouldCacheRequest (Request $request): bool {
		// TODO: Implement shouldCacheRequest() method.
		if ($request->ajax()) {
			return false;
		}
		if ($this->isRunningInConsole()) {
			return false;
		}
		return $request->isMethod('get');
	}

	public function shouldCacheResponse (Response $response): bool {
		// TODO: Implement shouldCacheResponse() method.
		return $response->isSuccessful() || $response->isRedirection();
	}

	/*
     * Set a string to add to differentiate this request from others.
     */
	public function cacheNameSuffix (Request $request): string {
		$sortCached = rand(1,20);
		$rootSuffix = $request->getHost() .'.'. $sortCached;
		if (Auth::check()) {
			return $rootSuffix . Auth::user()->id;
		}
		return $rootSuffix;
	}
}
