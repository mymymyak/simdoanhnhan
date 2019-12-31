<?php

namespace App\Services\Cache;

/**
 * Class CacheInterface.
 *
 * @author laven9696
 */
interface CacheInterface {

    /**
     * Retrieve data from cache.
     *
     * @param string    Cache item key
     *
     * @return mixed PHP data result of cache
     */
    public function get($key);

    /**
     * Add data to the cache.
     *
     * @param string    Cache item key
     * @param mixed     The data to store
     * @param int   The number of minutes to store the item
     *
     * @return mixed $value variable returned for convenience
     */
    public function put($key, $value, $minutes = null);

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired.
     *
     * @param string    Cache item key
     *
     * @return bool If cache item exists
     */
    public function has($key);
}
