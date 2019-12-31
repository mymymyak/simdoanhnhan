<?php
/**
 * Created by Navatech.
 * @project webgoi
 * @author  DuongNH
 * @email   duongnh.nava[at]gmail.com
 * @date    12/17/2019
 * @time    4:17 PM
 */

namespace App\Http\Response\ResponseCache;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;
use Spatie\ResponseCache\RequestHasher;
use Spatie\ResponseCache\ResponseCache;

use Symfony\Component\HttpFoundation\Response;

class AppResponseCache {

	/** @var \Spatie\ResponseCache\ResponseCache */
	protected $cache;

	/** @var \Spatie\ResponseCache\RequestHasher */
	protected $hasher;

	/** @var \Spatie\ResponseCache\CacheProfiles\CacheProfile */
	protected $cacheProfile;

	public function __construct (AppResponseCacheRepository $cache, RequestHasher $hasher, CacheProfile $cacheProfile) {
		$this->cache        = $cache;
		$this->hasher       = $hasher;
		$this->cacheProfile = $cacheProfile;
	}

	public function enabled (Request $request): bool {
		return $this->cacheProfile->enabled($request);
	}

	public function shouldCache (Request $request, Response $response): bool {
		if ($request->attributes->has('responsecache.doNotCache')) {
			return false;
		}
		if (!$this->cacheProfile->shouldCacheRequest($request)) {
			return false;
		}
		return $this->cacheProfile->shouldCacheResponse($response);
	}

	public function cacheResponse (Request $request, Response $response, $lifetimeInMinutes = null): Response {
		if (config('responsecache.add_cache_time_header')) {
			$response = $this->addCachedHeader($response);
		}
		$this->cache->put($this->hasher->getHashFor($request), $response, ($lifetimeInMinutes) ? intval($lifetimeInMinutes) : $this->cacheProfile->cacheRequestUntil($request));
		return $response;
	}

	public function hasBeenCached (Request $request): bool {
		return config('responsecache.enabled') ? $this->cache->has($this->hasher->getHashFor($request)) : false;
	}

	public function getCachedResponseFor (Request $request) {
		return $this->cache->getResponse($this->hasher->getHashFor($request));
	}

	/**
	 * @deprecated Use the new clear method, this is just an alias.
	 */
	public function flush () {
		$this->clear();
	}

	public function clear () {
		$this->cache->clear();
	}

	protected function addCachedHeader (Response $response): Response {
		$clonedResponse = clone $response;
		$clonedResponse->headers->set('laravel-responsecache', 'cached on ' . date('Y-m-d H:i:s'));
		return $clonedResponse;
	}

	/**
	 * @param string|array $uris
	 *
	 * @return \Spatie\ResponseCache\ResponseCache
	 */
	public function forget ($uris): self {
		$uris = is_array($uris) ? $uris : func_get_args();
		collect($uris)->each(function($uri) {
			$request = Request::create($uri);
			$hash    = $this->hasher->getHashFor($request);
			if ($this->cache->has($hash)) {
				$this->cache->forget($hash);
			}
		});
		return $this;
	}
}
