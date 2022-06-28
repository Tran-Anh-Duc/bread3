<?php

namespace Modules\ViewCpanel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Routing\Router;
use Modules\ViewCpanel\Providers\RouteServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ViewCpanelServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'ViewCpanel';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'viewcpanel';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ( !env("DISABLE_MODULE_VIEW_CPANEL", false) ) {
            $this->registerConfig();
            $this->registerFactories();
            $this->registerViews();
            $this->registerAssets();
            $this->registerTranslations();
            $this->loadMigrationsFrom($this->module_path('Database/Migrations'));
            $router = $this->app->make(Router::class);
            $router->aliasMiddleware('tokenIsValid', TokenIsValid::class);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
         $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            $this->module_path('Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->module_path('Config/config.php'), $this->moduleNameLower
        );

        $this->app['config']->set('domains', require $this->module_path('Config/domains.php'));

        $this->app['config']->set('routes', require $this->module_path('Config/routes.php'));
    }

    /**
     * Merges the configs together and takes multi-dimensional arrays into account.
     *
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    protected function mergeConfig(array $original, array $merging)
    {
        $array = array_merge($original, $merging);

        foreach ($original as $key => $value) {
            if (! is_array($value)) {
                continue;
            }

            if (! Arr::exists($merging, $key)) {
                continue;
            }

            if (is_numeric($key)) {
                continue;
            }

            $array[$key] = $this->mergeConfig($value, $merging[$key]);
        }

        return $array;
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            $this->loadFactoriesFrom($this->module_path($this->moduleName, 'Database/factories'));
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = $this->module_path('Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    public function registerAssets()
    {
        $viewPath = public_path($this->moduleNameLower);

        $sourcePath = $this->module_path('Resources/assets');

        $this->publishes([
            $sourcePath => $viewPath
        ], "assets");
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    private function module_path($path) {
        return __DIR__.'/../'. $path;
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom($this->module_path('Resources/lang'), $this->moduleName);
        }
    }
}
