<?php

namespace Orchids\XSetting\Providers;

use Orchid\Platform\Dashboard;

class MenuComposer
{
    /**
     * MenuComposer constructor.
     *
     * @param Dashboard $dashboard
     */
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
		//$this->menu = $dashboard->menu;
    }

    /**
     *
     */
    public function compose()
    {
        $this->dashboard->menu
            ->add('CMS', [
                'slug'       => 'XSetting',
                'icon'       => 'icon-settings',
                'route'      => route('platform.xsetting.list'),
                'label'      => 'Setting configuration',
                'groupname'  => trans('platform::systems/category.groupname'),
                /*'active'     => 'platform.systems.*',*/
                'permission' => 'platform.systems.xsetting',
                'sort'       => 7,
            ]);
    }
}