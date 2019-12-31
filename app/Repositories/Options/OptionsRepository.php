<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Options;

/**
 * Description of OptionsRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */
use App\Models\TableOptions;
use App\Repositories\CrudableInterface;
use App\Repositories\Options\OptionsInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class OptionsRepository extends RepositoryAbstract implements OptionsInterface, CrudableInterface {

    protected $options;
    protected $domain = null;

    protected static $rules = [
        'option_name' => 'required',
        //'option_value' => 'required',
        'domain' => 'required'
    ];

    public function __construct(TableOptions $options) {
        $this->options = $options;
        $this->domain = !empty($_COOKIE['domain_setting']) ? $_COOKIE['domain_setting'] : null;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function all() {
        return $this->options->get();
    }

    public function searchData($q) {
        return $this->options->select('option_name as id', 'option_name as text')->where('option_name', 'like', '%'.$q.'%')->get();
    }

    public function find($id) {
        return $this->options->findOrFail($id);
    }
    public function findByOptionName($optionName) {
        return $this->options->where('option_name', $optionName)->first();
    }
	public function findByOptionName2($optionName) {
        return $this->options->where('option_name', $optionName)->where('domain', $this->domain)->first();
    }
    public function findByOptionNameAndDomain($optionName, $domain) {
        return $this->options->where('option_name', $optionName)->where('domain', $domain)->first();
    }

    public function create($attributes) {
        $attributes['type'] = isset($attributes['type']) ? 1 : 0;
		if (!empty($attributes['id'])) {
			$options = $this->options->where('id', $attributes['id'])->first();
		} else {
			$options = $this->options->where('option_name', $attributes['option_name'])->where('domain', $this->domain)->first();
		}
        
        if ($options) {
            $this->options = $options;
        }
        unset($attributes['_token']);
        $attributes['option_value'] = json_encode($attributes, JSON_UNESCAPED_UNICODE);
        $attributes['domain'] = $this->domain;
        if ($this->isValid($attributes)) {
            $this->options->fill($attributes)->save();

            return true;
        }
        throw new ValidationException('Options validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
        $this->options = $this->find($id);
        if ($this->isValid($attributes)) {
            $this->options->fill($attributes)->save();
            return true;
        }

        throw new ValidationException('Options validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->options->findOrFail($id)->delete();
    }

    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {
        $query = $this->options->orderBy('id', 'DESC');
        if (!$all) {
            //$query->where('is_published', 1);
        }
        if (!empty($this->domain)) {
            $query->where('domain', $this->domain);
        }
        return $blogs = $query->paginate($limit);
    }

    protected function countItem() {
        return $this->options->count();
    }

}
