<?php
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class XSettingListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'settings';
    /**
     * @return array
     */
    public function columns() : array
    {
        return  [

			TD::set('key','Key')
                ->sort()
                ->filter('text')
                ->Render(function ($data) {
                    return Link::make($data->key)
                        ->route('platform.xsetting.edit', $data->key);
                }),
			TD::set('options.title', 'Name')
				->render(function ($xsetting) {
                    return $xsetting->options['title'];
				})
                ->sort(),
            TD::set('value','Value')
                ->render(function ($xsetting) {
                     if (is_array($xsetting->value)) {
                        return \Str::limit(htmlspecialchars(json_encode($xsetting->value)), 50);
                     }
                     return \Str::limit(htmlspecialchars($xsetting->value), 50);
				})
                ->sort()
                ->filter('text'),


        ];
    }
}