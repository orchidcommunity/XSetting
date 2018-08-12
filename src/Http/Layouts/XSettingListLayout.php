<?php
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\TD;

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
        //dd($data);
        return  [
            //TD::set('key','Key'),

			TD::set('key','Key')
                ->setRender(function ($xsetting) {
                return '<a href="' . route('platform.xsetting.edit',
                        $xsetting->key) . '">' . $xsetting->key . '</a>';
            }),
			TD::set('options.title', 'Name')
				->setRender(function ($xsetting) {
                return $xsetting->options['title'];
				}),
            TD::set('value','Value')
                ->setRender(function ($xsetting) {
                     if (is_array($xsetting->value)) {
                        return substr(htmlspecialchars(json_encode($xsetting->value)), 0, 50);
                     }
                     return substr(htmlspecialchars($xsetting->value), 0, 50);
				}),


        ];
		//dd($return);
    }
}