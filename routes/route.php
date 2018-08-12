<?php


Route::domain((string) config('platform.domain'))
    ->prefix(Dashboard::prefix('/systems'))
    ->middleware(config('platform.middleware.private'))
    ->namespace('Orchids\XSetting\Http\Screens')
    ->group(function (\Illuminate\Routing\Router $router, $path='platform.xsetting.') {
        $router->screen('xsetting/{xsetting}/edit', 'XSettingEdit',$path.'edit');
        $router->screen('xsetting/create', 'XSettingEdit',$path.'create');
        $router->screen('xsetting', 'XSettingList',$path.'list');
    });
/*
Route::group([
    'middleware' => ['web', 'platform'],	
    'prefix'     => 'dashboard/systems',
    'namespace'  => 'Orchids\XSetting\Http\Screens',
],
    function (\Illuminate\Routing\Router $router, $path='platform.xsetting.') {
		$router->screen('xsetting/{xsetting}/edit', 'XSettingEdit',$path.'edit');
		$router->screen('xsetting/create', 'XSettingEdit',$path.'create');
		$router->screen('xsetting', 'XSettingList',$path.'list');
    });	
*/
