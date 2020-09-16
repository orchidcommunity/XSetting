<?php
namespace Orchids\XSetting\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Illuminate\Support\Facades\Route;

class XSettingProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     * After change run:  php artisan vendor:publish --provider="Orchids\XSetting\Providers\XSettingProvider"
     * after: use Pingpong\Shortcode
     */
    public function boot(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        $this->registerTranslations();
        $this->loadMigrationsFrom(realpath(XSETTING_PATH.'/database/migrations'));
        $this->loadRoutesFrom(realpath(XSETTING_PATH.'/routes/route.php'));


        View::composer('platform::systems', MenuComposer::class);

        $this->app->booted(function () {
            $this->dashboard->registerPermissions($this->registerPermissions());
            $this->routes();
        });
    }

    /**
     * Register the Press service provider.
     */
    public function register()
    {
        if (! defined('XSETTING_PATH')) {
            /*
             * Get the path to the ORCHID Press folder.
             */
            define('XSETTING_PATH', realpath(__DIR__.'/../../'));
        }
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissions(): ItemPermission
    {
        return ItemPermission::group(__('Systems'))
            ->addPermission('platform.systems.xsetting', __('Edit settings'));
    }

    /**
     * Register translations.
     *
     * @return $this
     */
    public function registerTranslations(): self
    {
        $this->loadJsonTranslationsFrom(realpath(XSETTING_PATH.'/resources/lang/'));
        //$this->loadTranslationsFrom(realpath(XSETTING_PATH.'/resources/lang/'), 'xsetting');
        return $this;
    }

    /**
     * Get real path
     */
    public function getPath($path)
    {
        return realpath(__DIR__.'/../../'.$path);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/systems'))
            ->as('platform.xsetting.')
            ->middleware(config('platform.middleware.private'))
            ->group($this->getPath('/routes/route.php'));
    }

}