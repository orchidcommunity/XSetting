<?php
namespace Orchids\XSetting\Http\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Layouts;
use Orchid\Screen\Link;

use Orchids\XSetting\Models\XSetting;
use Orchids\XSetting\Http\Layouts\XSettingListLayout;

class XSettingList extends Screen
{
    /**
     * Display header name
     *
     * @var string
     */
    public $name = 'Setting List';
    /**
     * Display header description
     *
     * @var string
     */
    public $description = 'List all settings';
    /**
     * Query data
     *
     * @return array
     */
    public function query() : array
    {
         return [
            'settings' => XSetting::paginate(30)
        ];
    }
    /**
     * Button commands
     *
     * @return array
     */
    public function commandBar() : array
    {
        return [
            Link::name('Create a new setting')->method('create'),
        ];
    }
    /**
     * Views
     *
     * @return array
     */
    public function layout() : array
    {
        return [
            XSettingListLayout::class,
        ];
    }
    /**
     * @return null
     */
     public function create()
    {
        return redirect()->route('platform.xsetting.create');
    }
}