<?php
namespace Orchids\XSetting\Models;

use Illuminate\Support\Facades\Cache;
use Orchid\Setting\Setting;
use Orchid\Platform\Traits\FilterTrait;
use Orchid\Platform\Traits\MultiLanguageTrait;

class XSetting extends Setting
{
	use FilterTrait, MultiLanguageTrait;
	
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