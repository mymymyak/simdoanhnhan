<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 15-Jul-19
 * Time: 4:14 PM
 */

namespace App\Repositories\Orders;

use App\Services\Cache\CacheInterface;

class OrdersCache extends AbstractOrdersDecorator
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
    protected $cacheKey = '$orders';

    /**
     * @param OrdersInterface $orders
     * @param CacheInterface   $cache
     */
    public function __construct(OrdersInterface $orders, CacheInterface $cache) {
        parent::__construct($orders);
        $this->cache = $cache;
    }

    public function getLastestOrder($domain) {
        $key = $this->cacheKey . '.getLastestOrder'.$domain;

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $str = $this->orders->getLastestOrder($domain);
        $this->cache->put($key, $str);

        return $str;
    }
    public function create ($attributes){
        return $this->orders->create($attributes);
    }

	public function isDuplicatedOrder ($customerPhoneNumber, $orderSim) {
		return $this->orders->isDuplicatedOrder($customerPhoneNumber, $orderSim);
	}

    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {}
}
