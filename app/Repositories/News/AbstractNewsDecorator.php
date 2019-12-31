<?php

namespace App\Repositories\News;

/**
 * Class AbstractNewsDecorator.
 *
 * @author laven9696
 */
abstract class AbstractNewsDecorator implements NewsInterface {

    /**
     * @var AbstractNewsDecorator
     */
    protected $news;

    /**
     * @param NewsInterface $news
     */
    public function __construct(NewsInterface $news) {
        $this->news = $news;
    }

    public function find($id) {
        return $this->news->find($id);
    }

    public function findByAlias($alias, $domain) {
        return $this->news->findByAlias($alias, $domain);
    }

    public function getRelatedNews($domain, $currentId) {
        return $this->news->getRelatedNews($domain, $currentId);
    }
	public function getLastestNews($domain) {
        return $this->news->getLastestNews($domain);
    }

    public function paginateSimple($limit, $all = true) {
        return $this->news->paginateSimple($limit, $all = true);
    }
}