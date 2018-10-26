<?php
namespace Orchids\XSetting\Models;

use Illuminate\Support\Facades\Cache;
use Orchid\Setting\Setting;
use Orchid\Platform\Traits\MultiLanguageTrait;

class XSetting extends Setting
{
	use MultiLanguageTrait;
	
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