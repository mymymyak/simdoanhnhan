<?php

namespace App\Providers;

use App\Repositories\Orders\OrdersRepository;
use App\Services\Cache\AppCache;

use Illuminate\Support\ServiceProvider;
// blog
use App\Models\Blog;
use App\Repositories\Blog\BlogRepository;
//role
use App\Repositories\User\RoleRepository;
// news
use App\Models\TableNews;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\NewsCache as NewsCacheDecorator;

// pages
use App\Models\Pages;
use App\Repositories\Pages\PagesRepository;
use App\Repositories\Pages\PagesCache as PagesCacheDecorator;

// Option
use App\Models\TableOptions;
use App\Repositories\Options\OptionsRepository;

// Domain
use App\Models\TableDomain;
use App\Repositories\Domain\DomainRepository;
use App\Repositories\Domain\DomainCache as DomainCacheDecorator;

// Bangso
use App\Repositories\Bangso\BangsoRepository;

// Elastic
use App\Repositories\Elastic\ElasticQuery;
use App\Repositories\Elastic\ElasticCache as ElasticCacheDecorator;

// Orders
use App\Models\TableOrders;
use App\Repositories\Orders\OrdersCache as OrderCacheDecorator;



class RepositoriesServiceProvider extends ServiceProvider
{
    protected $defer = true;
    protected $isAdmin = false;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $this->app->request->segment(1);
        if ($this->app->request->segment(1) == 'admin') {
            $this->isAdmin = true;
        }

        $app->bind('App\Repositories\Blog\BlogInterface', function($app) {
            $blog = new BlogRepository(
                new Blog
            );
            return $blog;
        });
        $app->bind('App\Repositories\User\RoleInterface', function($app) {
            $role = new RoleRepository();
            return $role;
        });
        $app->bind('App\Repositories\News\NewsInterface', function($app) {
            $news = new NewsRepository(
                new TableNews
            );
            if ($app['config']->get('global.cache') === true && $this->isAdmin == false) {
                $news = new NewsCacheDecorator(
                    $news, new AppCache($app['cache'], 'orders')
                );
            }
            return $news;

        });
        $app->bind('App\Repositories\Pages\PagesInterface', function($app) {
            $news = new PagesRepository(
                new Pages()
            );
            if ($app['config']->get('global.cache') === true && $this->isAdmin == false) {
                $news = new PagesCacheDecorator(
                    $news, new AppCache($app['cache'], 'orders')
                );
            }
            return $news;
        });
        $app->bind('App\Repositories\Options\OptionsInterface', function($app) {
            $options = new OptionsRepository(
                new TableOptions()
            );
            return $options;
        });
        $app->bind('App\Repositories\Domain\DomainInterface', function($app) {
            $options = new DomainRepository(
                new TableDomain()
            );
            if ($app['config']->get('global.cache') === true && $this->isAdmin == false) {
                $options = new DomainCacheDecorator(
                    $options, new AppCache($app['cache'], 'domain')
                );
            }
            return $options;
        });
        $app->bind('App\Repositories\Elastic\ElasticInterface', function($app) {
            $elastic = new ElasticQuery();
            if ($app['config']->get('global.cache') === true && $this->isAdmin == false) {
                $elastic = new ElasticCacheDecorator(
                    $elastic, new AppCache($app['cache'], 'elastic')
                );
            }
            return $elastic;
        });
        $app->bind('App\Repositories\Orders\OrdersInterface', function($app) {
            $orders = new OrdersRepository(new TableOrders());
            if ($app['config']->get('global.cache') === true && $this->isAdmin == false) {
                $orders = new OrderCacheDecorator(
                    $orders, new AppCache($app['cache'], 'orders')
                );
            }
            return $orders;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
