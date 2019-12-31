<?php
/**
 * Created by PhpStorm.
 * User: DUYDUC
 * Date: 31-Jul-19
 * Time: 10:21 AM
 */

namespace App\ViewComposer;

use Illuminate\View\View;

use App\Repositories\Orders\OrdersInterface;

class OrderComposer
{
    protected $orders = null;

    public function __construct(OrdersInterface $orders)
    {
        $this->orders = $orders;
    }
    public function compose(View $view)
    {
        $view->with('lastestOrder', $this->orders->getLastestOrder(request()->server('HTTP_HOST')));
    }
}