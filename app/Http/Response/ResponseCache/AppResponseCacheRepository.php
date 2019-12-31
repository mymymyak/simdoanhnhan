<?php
/**
 * Created by Navatech.
 * @project webgoi
 * @author  DuongNH
 * @email   duongnh.nava[at]gmail.com
 * @date    12/17/2019
 * @time    4:27 PM
 */

namespace App\Http\Response\ResponseCache;

use Spatie\ResponseCache\ResponseCacheRepository;
use Symfony\Component\HttpFoundation\Response;

class AppResponseCacheRepository extends ResponseCacheRepository {

	/**
	 * @param string $key
	 *
	 * @return Response
	 * @throws \Spatie\ResponseCache\Exceptions\CouldNotUnserialize
	 */
	public function getResponse (string $key) {
		if ($this->cache->get($key) === null) {
			return null;
		}
		return $this->responseSerializer->unserialize($this->cache->get($key));
	}
}
