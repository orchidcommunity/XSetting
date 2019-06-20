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
    }

    /**
     *
     */
    public function compose()
    {
        $this->dashboard->menu
            ->add('CMS',
                ItemMenu::Label(__('Setting configuration'))
                    ->Slug('XSetting')
                    ->Icon('icon-settings')
                    ->title(__('Setting description'))
                    ->Route('platform.xsetting.list')
                    ->Permission('platform.systems.xsetting')
                    ->Sort(7)
            );
    }
}