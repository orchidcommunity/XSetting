<?php
namespace Orchids\XSetting\Models;

use Illuminate\Support\Facades\Cache;
use Orchid\Filters\Filterable;
use Orchid\Setting\Setting;
use Orchid\Screen\AsMultiSource;

class XSetting extends Setting
{
	use Filterable, AsMultiSource;
	
	protected $fillable = [
		'key',
        'value',
        'options',
    ];	

	protected $casts = [
        'key' =>'string',
        'value' => 'array',
        'options' => 'array',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'key',
        'value',
    ];
    /**
     * @var array
     */
    protected $allowedSorts = [
        'key',
        'value',
    ];
}