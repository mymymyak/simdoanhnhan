<?php

namespace App\Repositories\Pages;

/**
 * Class AbstractPagesDecorator.
 *
 * @author laven9696
 */
abstract class AbstractPagesDecorator implements PagesInterface {

    /**
     * @var AbstractPagesDecorator
     */
    protected $pages;

    /**
     * @param PagesInterface $pages
     */
    public function __construct(PagesInterface $pages) {
        $this->pages = $pages;
    }

    public function find($id) {
        return $this->pages->find($id);
    }

    public function findByAlias($alias, $domain) {
        return $this->pages->findByAlias($alias, $domain);
    }

    public function paginateSimple($limit, $all = true) {
        return $this->pages->paginateSimple($limit, $all = true);
    }
}