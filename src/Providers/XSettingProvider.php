<?php
namespace Orchids\XSetting\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

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
        //$this->dashboard->registerPermissions($this->registerPermissions());
        $this->loadMigrationsFrom(realpath(XSETTING_PATH.'/database/migrations'));
        $this->loadRoutesFrom(realpath(XSETTING_PATH.'/routes/route.php'));


        //View::composer('platform::layouts.dashboard', MenuComposer::class);
        View::composer('platform::systems', MenuComposer::class);

        $this->app->booted(function () {
            $this->dashboard->registerPermissions($this->registerPermissions());
            //$this->registerTranslations();
        });
        //dd($this->app['translator']);
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
}