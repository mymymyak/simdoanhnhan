<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 4:14 PM
 */

namespace App\Repositories\News;

use App\Services\Cache\CacheInterface;

class NewsCache extends AbstractNewsDecorator
{

    /**
     * @var \App\Services\Cache\CacheInterface
     */
    protected $cache;
    protected $cacheTime = 3600;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = '$news';

    /**
     * @param NewsInterface $news
     * @param CacheInterface   $cache
     */
    public function __construct(NewsInterface $news, CacheInterface $cache) {
        parent::__construct($news);
        $this->cache = $cache;
    }

    public function find($id){
	    $key = $this->cacheKey.$id;

	    if ($this->cache->has($key)) {
		    return $this->cache->get($key);
	    }
	    $str = $this->news->find($id);
	    $this->cache->put($key, $str, $this->cacheTime);

	    return $str;
    }
    public function getLastestNews($domain) {
        $key = $this->cacheKey . '.getLastestNews'.$domain;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->news->getLastestNews($domain);
        $this->cache->put($key, $str, $this->cacheTime);

        return $str;
    }

	public function getRelatedNews($domain, $currentId) {
		$key = $this->cacheKey . '.getRelatedNews'.$domain.$currentId;
		if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

		$str = $this->news->getRelatedNews($domain, $currentId);
        $this->cache->put($key, $str, $this->cacheTime);

		return $str;
	}

    public function findByAlias($alias, $domain) {
        $key = $this->cacheKey . '.findByAlias'.$alias;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->news->findByAlias($alias, $domain);
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
}
