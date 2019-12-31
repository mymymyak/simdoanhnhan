<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 4:14 PM
 */

namespace App\Repositories\Domain;

use App\Services\Cache\CacheInterface;

class DomainCache extends AbstractDomainDecorator
{

    /**
     * @var \App\Services\Cache\CacheInterface
     */
    protected $cache;
    protected $cacheTime = 1440;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = '$domain';

    /**
     * @param DomainInterface $domain
     * @param CacheInterface   $cache
     */
    public function __construct(DomainInterface $domain, CacheInterface $cache) {
        parent::__construct($domain);
        $this->cache = $cache;
    }

    public function findByDomainName($domain) {
        $key = $this->cacheKey . '.findByDomainName'. $domain;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->domainMd->findByDomainName($domain);
        $this->cache->put($key, $str, $this->cacheTime);

        return $str;
    }

    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {
        $page = request()->get('page');
        $key = $this->cacheKey . '.paginateSimple'.$page. $limit.(string)$all;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->news->paginateSimple($limit, $all = true);
        $this->cache->put($key, $str, $this->cacheTime);

        return $str;
    }

	public function paginateByDomain ($limit, $domain, $all = true) {
		if ($domain == null) {
			return $this->paginateSimple($limit, $all);
		}
		$page = request()->get('page');
		$key  = $this->cacheKey . '.paginateSimple.' . $domain . '.' . $page . $limit . (string) $all;
		if ($this->cache->has($key)) {
			return $this->cache->get($key);
		}
		$str = $this->news->paginateSimple($limit, $all = true);
		$this->cache->put($key, $str, $this->cacheTime);
		return $str;
	}
}
