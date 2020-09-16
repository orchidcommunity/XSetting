<?php

use Orchids\XSetting\Http\Screens\XSettingEdit;
use Orchids\XSetting\Http\Screens\XSettingList;


Route::screen('xsetting/{xsetting}/edit', XSettingEdit::class)
    ->name('edit');
Route::screen('xsetting/create', XSettingEdit::class)
    ->name('create');
Route::screen('xsetting', XSettingList::class)
    ->name('list');