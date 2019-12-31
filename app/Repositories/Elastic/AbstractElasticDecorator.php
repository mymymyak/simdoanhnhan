<?php

namespace App\Repositories\Elastic;

/**
 * Class AbstractElasticDecorator.
 *
 * @author laven9696
 */
abstract class AbstractElasticDecorator implements ElasticInterface {

    /**
     * @var AbstractElasticDecorator
     */
    protected $elastic;

    /**
     * @param ElasticInterface $elastic
     */
    public function __construct(ElasticInterface $elastic) {
        $this->elastic = $elastic;
    }

    public function searchQuery($params) {
        return $this->elastic->searchQuery($params);
    }

    public function search ($params) {
        return $this->elastic->search($params);
    }

    public function searchByTerms($listPhoneNumber, $limit){
	    return $this->elastic->searchByTerms($listPhoneNumber, $limit);
    }
}
