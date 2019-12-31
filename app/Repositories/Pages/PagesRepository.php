<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Pages;

/**
 * Description of PagesRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */

use App\Models\Pages;
use App\Repositories\CrudableInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class PagesRepository extends RepositoryAbstract implements PagesInterface, CrudableInterface {

    protected $pages;
    protected $domain = null;

    protected static $rules = [
        'title' => 'required',
        'detail' => 'required',
    ];

    public function __construct(Pages $pages) {
        $this->pages = $pages;
        $this->domain = isset($_COOKIE['domain_setting']) ? $_COOKIE['domain_setting'] : null;
    }

    public function all() {
        return $this->pages->get();
    }

    public function find($id) {
        return $this->pages->findOrFail($id);
    }

    public function findByAlias($alias, $domain) {
        return $this->pages->where('slug', $alias)->where('domain', $domain)->first();
    }

    public function create($attributes) {
        $attributes['flag_all'] = isset($attributes['flag_all']) ? 1 : 0;
        if ($this->isValid($attributes)) {
            $this->pages->fill($attributes)->save();

            return true;
        }
        throw new ValidationException('Pages validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
        $attributes['flag_all'] = isset($attributes['flag_all']) ? 1 : 0;
        $this->pages = $this->find($id);
        if ($this->isValid($attributes)) {
            $this->pages->fill($attributes)->save();
            return true;
        }

        throw new ValidationException('Pages validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->pages->findOrFail($id)->delete();
    }

    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {
        $query = $this->pages->orderBy('id', 'DESC');
        $query->where('flag_all', 1);
        if (!empty($this->domain)) {
            $query->orWhere('domain', $this->domain);
        }
        return $blogs = $query->paginate($limit);
    }

    protected function countItem() {
        return $this->pages->count();
    }

}
