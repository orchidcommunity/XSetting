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
                ItemMenu::Label('Setting configuration')
                    ->Slug('XSetting')
                    ->Icon('icon-settings')
                    ->Route('platform.xsetting.list')
                    ->Permission('platform.systems.xsetting')
                    ->Sort(7)
            );
    }
}