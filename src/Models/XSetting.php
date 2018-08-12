<?php
namespace Orchids\XSetting\Models;

use Illuminate\Support\Facades\Cache;
use Orchid\Setting\Setting;
use Orchid\Platform\Traits\MultiLanguage;

class XSetting extends Setting
{
	use MultiLanguage;
	
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
}