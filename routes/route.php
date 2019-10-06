<?php

use Orchids\XSetting\Http\Screens\XSettingEdit;
use Orchids\XSetting\Http\Screens\XSettingList;



Route::domain((string) config('platform.domain'))
    ->prefix(Dashboard::prefix('/systems'))
    ->middleware(config('platform.middleware.private'))
    ->group(function (\Illuminate\Routing\Router $router, $path='platform.xsetting.') {
        $router->screen('xsetting/{xsetting}/edit', XSettingEdit::class)->name($path.'edit');
        $router->screen('xsetting/create', XSettingEdit::class)->name($path.'create');
        $router->screen('xsetting', XSettingList::class)->name($path.'list');
    });

