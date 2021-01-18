<?php

namespace Orchids\XSetting\Providers;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\Menu;

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
            ->add(Menu::SYSTEMS,
                ItemMenu::label('Settings')
                    ->slug('setting')
                    ->icon('layers')
                    ->permission('platform.systems.xsetting')
                    ->sort(100)
            )
            ->add('setting',
                ItemMenu::Label(__('Setting configuration'))
                    ->Slug('XSetting')
                    ->Icon('settings')
                    ->title(__('Setting description'))
                    ->Route('platform.xsetting.list')
                    ->Permission('platform.systems.xsetting')
                    ->Sort(7)
            );
    }
}
