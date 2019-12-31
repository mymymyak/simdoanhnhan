<?php

namespace App\Http\Response\ResponseCache;

use Illuminate\Http\Request;
use Spatie\ResponseCache\Events\CacheMissed;
use Spatie\ResponseCache\Events\ResponseCacheHit;
use Symfony\Component\HttpFoundation\Response;

class AppCacheResponse {

	/** @var \Spatie\ResponseCache\ResponseCache */
	protected $responseCache;

	public function __construct (AppResponseCache $responseCache) {
		$this->responseCache = $responseCache;
	}

	public function handle (Request $request, \Closure $next, $lifetimeInMinutes = null): Response {
		if ($this->responseCache->enabled($request)) {
			if ($this->responseCache->hasBeenCached($request)) {
				event(new ResponseCacheHit($request));
				$resp = $this->responseCache->getCachedResponseFor($request);
				if($resp != null){
					return $resp;
				}
			}
		}
		$response = $next($request);
		if ($this->responseCache->enabled($request)) {
			if ($this->responseCache->shouldCache($request, $response)) {
				$this->responseCache->cacheResponse($request, $response, $lifetimeInMinutes);
			}
		}
		event(new CacheMissed($request));
		return $response;
	}
}
