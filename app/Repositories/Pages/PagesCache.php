<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 4:14 PM
 */

namespace App\Repositories\Pages;

use App\Services\Cache\CacheInterface;

class PagesCache extends AbstractPagesDecorator
{

    /**
     * @var \App\Services\Cache\CacheInterface
     */
    protected $cache;
    protected $cacheTime = 60;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = '$pages';

    /**
     * @param PagesInterface $pages
     * @param CacheInterface   $cache
     */
    public function __construct(PagesInterface $pages, CacheInterface $cache) {
        parent::__construct($pages);
        $this->cache = $cache;
    }

    public function findByAlias($alias, $domain) {
        $key = $this->cacheKey . '.findByAlias'.$alias;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->pages->findByAlias($alias, $domain);
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
        $str = $this->pages->paginateSimple($limit, $all = true);
        $this->cache->put($key, $str, $this->cacheTime);

        return $str;
    }
}