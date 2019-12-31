<?php

namespace Zkiller\Filemanager;

use Illuminate\Support\ServiceProvider;

class FilemanagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/zkiller_upload.php' => config_path('zkiller_upload.php'),
        ], 'zkiller_upload');
        
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/zkiller/filemanager'),
        ]);
        
        $this->publishes([
            __DIR__.'/public' => public_path('zkiller_upload'),
        ]);
        
        $this->loadViewsFrom(__DIR__.'/views', 'filemanager');
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Zkiller\Filemanager\Controllers\FileManagerController');
    }
}
