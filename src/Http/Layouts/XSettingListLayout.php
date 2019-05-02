<?php
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class XSettingListLayout extends Table
{
    /**
     * @var string
     */
    public $data = 'settings';
    /**
     * @return array
     */
    public function fields() : array
    {
        return  [

			TD::set('key','Key')
                ->link('platform.xsetting.edit','key','key')
                ->sort()
                ->filter('text'),
			TD::set('options.title', 'Name')
				->render(function ($xsetting) {
                return $xsetting->options['title'];
				})
                ->sort(),
            TD::set('value','Value')
                ->render(function ($xsetting) {
                     if (is_array($xsetting->value)) {
                        return str_limit(htmlspecialchars(json_encode($xsetting->value)), 50);
                     }
                     return str_limit(htmlspecialchars($xsetting->value), 50);
				})
                ->sort()
                ->filter('text'),


        ];
    }
}