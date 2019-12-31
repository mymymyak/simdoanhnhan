<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 4:14 PM
 */

namespace App\Repositories\Elastic;

use App\Services\Cache\CacheInterface;

class ElasticCache extends AbstractElasticDecorator
{

    /**
     * @var \App\Services\Cache\CacheInterface
     */
    protected $cache;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = '$elastic';

    /**
     * @param ElasticInterface $elastic
     * @param CacheInterface   $cache
     */
    public function __construct(ElasticInterface $elastic, CacheInterface $cache) {
        parent::__construct($elastic);
        $this->cache = $cache;
    }

    public function searchQuery($params) {
        $keyEn = serialize($params);
        $key = $this->cacheKey . '.searchQuery.' . md5($keyEn);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->elastic->searchQuery($params);
        $this->cache->put($key, $str);

        return $str;
    }

    public function search ($params) {
        $keyEn = serialize($params);
        $key = $this->cacheKey . '.search.' . md5($keyEn);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->elastic->search($params);
        $this->cache->put($key, $str, 5);

        return $str;
    }

	public function searchByTerms ($listPhoneNumber, $limit) {
		// TODO: Change the auto generated stub
		$keyEn = serialize($listPhoneNumber);
		$key   = $this->cacheKey . '.search.' . md5($keyEn);
		if ($this->cache->has($key)) {
			//return $this->cache->get($key);
		}
		$str = $this->elastic->searchByTerms($listPhoneNumber, $limit);
		$this->cache->put($key, $str, 5);
		return $str;
	}

	public function clearSearchCache ($params) {
		$keyEn = serialize($params);
		$key   = $this->cacheKey . '.search.' . md5($keyEn);
		if ($this->cache->has($key)) {
			return $this->cache->forget($key);
		}
	}
}
