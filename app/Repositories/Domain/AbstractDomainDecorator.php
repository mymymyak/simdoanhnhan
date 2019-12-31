<?php

namespace App\Repositories\Domain;

/**
 * Class AbstractDomainDecorator.
 *
 * @author laven9696
 */
abstract class AbstractDomainDecorator implements DomainInterface {

    /**
     * @var AbstractDomainDecorator
     */
    protected $domainMd;

    /**
     * @param DomainInterface $domain
     */
    public function __construct(DomainInterface $domain) {
        $this->domainMd = $domain;
    }

    public function find($id) {
        return $this->domainMd->find($id);
    }

    public function findByAlias($alias, $domain) {
        return $this->news->findByAlias($alias, $domain);
    }

    public function getDomainActive() {
        return $this->domainMd->getDomainActive();
    }
    public function findByDomainName($domain) {
        return $this->domainMd->findByDomainName($domain);
    }

    public function paginateSimple($limit, $all = true) {
        return $this->domainMd->paginateSimple($limit, $all = true);
    }

	public function updateAttributes($id, $attributes){
		$this->domainMd = $this->find($id);
		return $this->domainMd->update($attributes);
	}
}
