<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\News;

/**
 * Description of NewsRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */
use App\Models\TableNews;
use App\Repositories\CrudableInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class NewsRepository extends RepositoryAbstract implements NewsInterface, CrudableInterface {

    protected $news;
    protected $domain = null;

    protected static $rules = [
        'title' => 'required',
        'description' => 'required',
        'detail' => 'required',
        'status' => 'required',
        'domain' => 'required'
    ];

    public function __construct(TableNews $news) {
        $this->news = $news;
        $this->domain = isset($_COOKIE['domain_setting']) ? $_COOKIE['domain_setting'] : null;
    }

    public function all() {
        return $this->news->get();
    }

    public function find($id) {
        $query = $this->news;
        if (!isAdmin()) {
            $query->where('domain', $this->domain);
        }
        return $query->findOrFail($id);
    }

    public function findByAlias($alias, $domain) {
        return $this->news->where('slug', $alias)->where('domain', $domain)->first();
    }
	
	public function getRelatedNews($domain, $currentId) {
		$query = $this->news->where('domain', $domain)->orderBy('id', 'DESC');
        if (!empty($currentId)) {
            $query->where('id', '<>', $currentId);
        }
		
		return $query->limit(5)->get();
	}

    public function create($attributes) {
        $attributes['domain'] = $this->domain;
        $attributes['status'] = 'binhthuong';
        $attributes['group_id'] = 1;
        $attributes['flag_all'] = 0;
        if ($this->isValid($attributes)) {
            $this->news->fill($attributes)->save();

            return true;
        }
        throw new ValidationException('News validation failed', $this->getErrors());
    }

    public function getLastestNews($domain) {
        return $this->news->where('domain', $domain)->orderBy('id', 'DESC')->limit(5)->get();
    }

    public function update($id, $attributes) {
        $attributes['domain'] = $this->domain;
        $attributes['status'] = 'binhthuong';
        $this->news = $this->find($id);
        if ($this->isValid($attributes)) {
            $this->news->fill($attributes)->save();
            return true;
        }

        throw new ValidationException('Blog validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->news->findOrFail($id)->delete();
    }

    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {
        $query = $this->news->orderBy('id', 'DESC');
        $query->where('flag_all', 1);
        if (!empty($this->domain)) {
            $query->orWhere('domain', $this->domain);
        }
        return $blogs = $query->paginate($limit);
    }

    protected function countItem() {
        return $this->news->count();
    }

}
