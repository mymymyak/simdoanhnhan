<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', 'App\ViewComposer\SiteComposer');
        View::composer('*', 'App\ViewComposer\OrderComposer');
        View::composer('*', 'App\ViewComposer\NewsComposer');
    }
}
