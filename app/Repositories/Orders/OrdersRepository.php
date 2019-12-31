<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Orders;

/**
 * Description of OrdersRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */
use App\Models\TableOrders;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class OrdersRepository extends RepositoryAbstract implements OrdersInterface {

    protected $orders;
    protected $domain = null;

    protected static $rules = [
        'sosim' => 'required',
        'siminfo' => 'required',
        'dienthoai' => 'required',
        'ip' => 'required',
    ];
    protected static $message = [
        'required' => ':attribute không được bỏ trống'
    ];
    protected static $niceNames = [
        'sosim' => 'Số cần mua',
        'dienthoai' => 'Điện thoại'
    ];

    public function __construct(TableOrders $orders) {
        $this->orders = $orders;
        $this->domain = isset($_COOKIE['domain_setting']) ? $_COOKIE['domain_setting'] : null;
    }

    public function find($id) {
        return $this->orders->findOrFail($id);
    }

    public function getLastestOrder($domain) {
        return $this->orders->where('domain', $domain)->orderBy('id', 'DESC')->limit(10)->get();
    }

    public function create($attributes) {
		$yeucau =session('refererList');
		$attributes['yeucau'] = is_array($yeucau) ? implode("\n", $yeucau) : $yeucau;
		$attributes['yeucau'] = nl2br($attributes['yeucau']);
		$attributes['yeucau'] = str_replace('https://'.$this->domain,"",$attributes['yeucau']);
		$attributes['yeucau'] = str_replace('http://'.$this->domain,"",$attributes['yeucau']);
        if ($this->isValid($attributes, static::$rules, static::$niceNames, static::$message)) {
            $countKhachquen = $this->orders->where('dienthoai', $attributes['dienthoai'])->count();
            $attributes['khachquen'] = $countKhachquen;
            $this->orders->fill($attributes)->save();
            return true;
        }
        throw new ValidationException('Orders validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
        $this->orders = $this->find($id);
        if ($this->isValid($attributes)) {
            $this->orders->fill($attributes)->save();
            return true;
        }

        throw new ValidationException('Orders validation failed', $this->getErrors());
    }

	public function isDuplicatedOrder ($customerPhoneNumber, $orderSim) {
    	$order = $this->orders->whereDienthoaiAndSosim($customerPhoneNumber,$orderSim)->where('ngaydathang','>=',date('Y-m-d H:i:s', time() - 24 * 60 *60))->first();
		return $order;
	}
    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {}
}
