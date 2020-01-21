<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repositories\Domain;

/**
 * Description of DomainRepository
 *
 * @author ducdd6647<ducdd6647@co-well.com.vn>
 */
use App\Models\TableDomain;
use App\Repositories\CrudableInterface;
use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class DomainRepository extends RepositoryAbstract implements DomainInterface, CrudableInterface {

    protected $domainMd;

    protected static $rules = [
        'domain' => 'required',
        'domain_name' => 'required',
    ];

    public function __construct(TableDomain $domainMd) {
        $this->domainMd = $domainMd;
    }

    public function all() {
        return $this->domainMd->get();
    }

    public function find($id) {
        return $this->domainMd->findOrFail($id);
    }

    public function findByDomainName($domain) {
       // dd($domain);
        return $this->domainMd->where('domain', $domain)->first();
    }

    public function create($attributes) {
        $attributes['active'] = isset($attributes['active']) && $attributes['active'] == 1 ? 1 : 0;
        $config = [
            'main_color' => isset($attributes['main_color']) ? $attributes['main_color'] : '',
            'favicon' => isset($attributes['favicon']) ? $attributes['favicon'] : '',
            'logo' => isset($attributes['logo']) ? $attributes['logo']: '',
            'hotline_open' => isset($attributes['hotline_open']) ? $attributes['hotline_open'] : '',
            'hotline_close' => isset($attributes['hotline_close']) ? $attributes['hotline_close'] : '',
            'ads_left' => isset($attributes['ads_left']) ? $attributes['ads_left'] : '',
            'ads_right' => isset($attributes['ads_right']) ? $attributes['ads_right'] : '',
            'ads_left_url' => isset($attributes['ads_left_url']) ? $attributes['ads_left_url'] : '',
            'ads_right_url' => isset($attributes['ads_right_url']) ? $attributes['ads_right_url'] : '',
            'condau' => isset($attributes['condau']) ? $attributes['condau'] : '',
            'url_301' => isset($attributes['url_301']) ? $attributes['url_301'] : '',
            'chat_script' => isset($attributes['chat_script']) ? $attributes['chat_script'] : '',
            'footer_box_1' => isset($attributes['footer_box_1']) ? $attributes['footer_box_1'] : '',
            'footer_box_2' => isset($attributes['footer_box_2']) ? $attributes['footer_box_2'] : '',
            'footer_box_3' => isset($attributes['footer_box_3']) ? $attributes['footer_box_3'] : '',
        ];
        $attributes['config'] = json_encode($config);
        if ($this->isValid($attributes)) {
            $this->domainMd->fill($attributes);
            $this->domainMd->save();

            return true;
        }
        throw new ValidationException('Domains validation failed', $this->getErrors());
    }

    public function update($id, $attributes) {
	    $this->domainMd = $this->find($id);
	    if($this->domainMd){
		    $attributes['active'] = isset($attributes['active']) && $attributes['active'] == 1 ? 1 : 0;
		    $attributes['highlights_number'] = isset($attributes['highlights_number'])
		                                       && $attributes['highlights_number'] == 1 ? 1 : 0;
		    $config = [
			    'main_color' => isset($attributes['main_color']) ? $attributes['main_color'] : '',
			    'favicon' => isset($attributes['favicon']) ? $attributes['favicon'] : '',
			    'logo' => isset($attributes['logo']) ? $attributes['logo']: '',
			    'logo_mobile' => isset($attributes['logo_mobile']) ? $attributes['logo_mobile']: '',
			    'hotline_open' => isset($attributes['hotline_open']) ? $attributes['hotline_open'] : '',
			    'hotline_close' => isset($attributes['hotline_close']) ? $attributes['hotline_close'] : '',
			    'ads_left' => isset($attributes['ads_left']) ? $attributes['ads_left'] : '',
			    'ads_right' => isset($attributes['ads_right']) ? $attributes['ads_right'] : '',
			    'ads_left_url' => isset($attributes['ads_left_url']) ? $attributes['ads_left_url'] : '',
			    'ads_right_url' => isset($attributes['ads_right_url']) ? $attributes['ads_right_url'] : '',
			    'condau' => isset($attributes['condau']) ? $attributes['condau'] : '',
			    'url_301' => isset($attributes['url_301']) ? $attributes['url_301'] : '',
			    'chat_script' => isset($attributes['chat_script']) ? $attributes['chat_script'] : '',
			    'footer_box_1' => isset($attributes['footer_box_1']) ? $attributes['footer_box_1'] : '',
			    'footer_box_2' => isset($attributes['footer_box_2']) ? $attributes['footer_box_2'] : '',
			    'footer_box_3' => isset($attributes['footer_box_3']) ? $attributes['footer_box_3'] : '',
		    ];
		    $attributes['config'] = json_encode($config);

		    if ($this->isValid($attributes)) {
			    $this->domainMd->fill($attributes)->save();
			    return true;
		    }
	    }
        throw new ValidationException('Domains validation failed', $this->getErrors());
    }

    public function delete($id) {
        $this->domainMd->findOrFail($id)->delete();
    }

    public function getDomainActive() {
        return $this->domainMd->where('active',1)->orderBy('updated_at', 'ASC')->get();
    }
    public function paginate($page = 1, $limit = 10, $all = true) {}

    public function paginateSimple($limit, $all = true) {
        $query = $this->domainMd->orderBy('id', 'DESC');
        return $query->paginate($limit);
    }

    public function paginateByDomain($limit, $domain, $all = true){
		if($domain == null ){
			return $this->paginateSimple($limit, $all);
		}
	    $query = $this->domainMd->whereDomain($domain)->orderBy('id', 'DESC');
	    return $query->paginate($limit);
    }

    public function updateAttributes($id, $attributes){
	    $this->domainMd = $this->find($id);
	    return $this->domainMd->update($attributes);
    }
}
