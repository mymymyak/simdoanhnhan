<?php

namespace App\Repositories\Orders;

/**
 * Class AbstractOrdersDecorator.
 *
 * @author laven9696
 */
abstract class AbstractOrdersDecorator implements OrdersInterface {

    /**
     * @var AbstractOrdersDecorator
     */
    protected $orders;

    /**
     * @param OrdersInterface $orders
     */
    public function __construct(OrdersInterface $orders) {
        $this->orders = $orders;
    }

    public function getLastestOrder($domain) {
        return $this->orders->getLastestOrder($domain);
    }
    public function create ($attributes){
        return $this->orders->create($attributes);
    }
}
