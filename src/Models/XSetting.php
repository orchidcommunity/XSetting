<?php
namespace Orchids\XSetting\Models;

use Illuminate\Support\Arr;
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

    /**
     * @param string|array $key
     *
     * @return null
     */
    public function cacheErase($key)
    {
        foreach (Arr::wrap($key) as $value) {
            Cache::forget(self::CACHE_PREFIX.$value);
        }
    }

}