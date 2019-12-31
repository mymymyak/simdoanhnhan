<?php

namespace Zkiller\Widget\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Support\Services\Cache\Cache;
use Zkiller\Widget\Commands\WidgetCreateCommand;
use Zkiller\Widget\Commands\WidgetRemoveCommand;
use Zkiller\Widget\Facades\AsyncFacade;
use Zkiller\Widget\Facades\WidgetFacade;
use Zkiller\Widget\Facades\WidgetGroupFacade;
use Zkiller\Widget\Factories\AsyncWidgetFactory;
use Zkiller\Widget\Factories\WidgetFactory;
use Zkiller\Widget\Misc\LaravelApplicationWrapper;
use Zkiller\Widget\Models\Widget;
use Zkiller\Widget\Repositories\Caches\WidgetCacheDecorator;
use Zkiller\Widget\Repositories\Eloquent\WidgetRepository;
use Zkiller\Widget\Repositories\Interfaces\WidgetInterface;
use Zkiller\Widget\WidgetGroupCollection;
use Zkiller\Widget\Widgets\Text;
use Event;
use File;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use WidgetGroup;

class WidgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(WidgetInterface::class, function () {
                return new WidgetCacheDecorator(new WidgetRepository(new Widget()), new Cache($this->app['cache'], WidgetRepository::class));
            });
        } else {
            $this->app->singleton(WidgetInterface::class, function () {
                return new WidgetRepository(new Widget());
            });
        }

        $this->app->bind('botble.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->bind('botble.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->singleton('botble.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper());
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                WidgetCreateCommand::class,
                WidgetRemoveCommand::class,
            ]);
        }

        $this->app->alias('botble.widget', WidgetFactory::class);
        $this->app->alias('botble.async-widget', AsyncWidgetFactory::class);
        $this->app->alias('botble.widget-group-collection', WidgetGroupCollection::class);

        AliasLoader::getInstance()->alias('Widget', WidgetFacade::class);
        AliasLoader::getInstance()->alias('AsyncWidget', AsyncFacade::class);
        AliasLoader::getInstance()->alias('WidgetGroup', WidgetGroupFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setIsInConsole($this->app->runningInConsole())
            ->setNamespace('core/widget')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssetsFolder()
            ->publishPublicFolder();

        WidgetGroup::setGroup([
            'id' => 'primary_sidebar',
            'name' => 'Primary sidebar',
            'description' => 'This is primary sidebar section',
        ]);

        register_widget(Text::class);

        $widget_path = public_path('themes/' . setting('theme') . '/widgets');
        $widgets = scan_folder($widget_path);
        if (!empty($widgets) && is_array($widgets)) {
            foreach ($widgets as $widget) {
                $registration = $widget_path . '/' . $widget . '/registration.php';
                if (File::exists($registration)) {
                    File::requireOnce($registration);
                }
            }
        }

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-widget',
                    'priority' => 3,
                    'parent_id' => 'cms-core-appearance',
                    'name' => trans('core.base::layouts.widgets'),
                    'icon' => null,
                    'url' => route('widgets.list'),
                    'permissions' => ['widgets.list'],
                ]);

            admin_bar()->registerLink('Widget', route('widgets.list'), 'appearance');
        });
    }
}
