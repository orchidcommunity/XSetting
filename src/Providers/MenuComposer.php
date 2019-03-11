<?php

namespace Orchids\XSetting\Providers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;

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
            ->add('CMS',
                ItemMenu::setLabel('Setting configuration')
                    ->setSlug('XSetting')
                    ->setIcon('icon-settings')
                    ->setRoute('platform.xsetting.list')
                    ->setPermission('platform.systems.xsetting')
                    ->setSort(7)
            );
    }
}