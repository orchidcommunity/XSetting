<?php
namespace Orchids\XSetting\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Orchid\Platform\Dashboard;
//use Pingpong\Shortcode\ShortcodeServiceProvider as SCServiceProvider;

class XSettingProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     * After change run:  php artisan vendor:publish --provider="Orchids\XSetting\Providers\XSettingProvider"
     */
    public function boot(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
        //$this->app->register(SCServiceProvider::class);
        $this->dashboard->registerPermissions($this->registerPermissions());
        $this->loadMigrationsFrom(realpath(__DIR__.'/../../database/migrations'));
   		$this->loadRoutesFrom(realpath(__DIR__.'/../../routes/route.php'));  //Файл роутинга

        //View::composer('platform::layouts.dashboard', MenuComposer::class);
        View::composer('platform::container.systems.index', MenuComposer::class);
    }

    /**
     * @return array
     */
    protected function registerPermissions(): array
    {
        return [
            trans('platform::permission.main.systems') => [
                [
                    'slug' => 'platform.systems.xsetting',
                    'description' => 'Edit settings',
                ],
            ],
        ];
    }
}