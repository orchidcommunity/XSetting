<?php
namespace Orchids\XSetting\Http\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Setting;
use Orchid\Screen\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

use Orchids\XSetting\Models\XSetting;
use Orchids\XSetting\Http\Layouts\XSettingEditLayout;


class XSettingEdit extends Screen
{
	
    /**
     * Display header name
     *
     * @var string
     */
    public $name = 'Setting edit';
    /**
     * Display header description
     *
     * @var string
     */
    public $description = 'Edit setting';
    /**
     * Edit or add setting
     *
     * @var boolean
     */
    public $edit=true;
    /**
     * Query data
     *
     * @param XSetting $xsetting
     *
     * @return array
     */
    public function query($xsetting = null) : array
    {
        if (is_null($xsetting)) {
            $xsetting = new XSetting();
            $this->edit = false;
            $this->name = __('New setting');
            $this->description = __('Add new setting');
        } else {
            $xsetting = XSetting::where("key",$xsetting)->first();
            $this->edit = true;
            $this->name = __('Edit setting').' '.$xsetting->key;
            if (!is_null($xsetting->options['title'])) {
                $this->description = $xsetting->options['title'];
            }
        }

        return [
            'xsetting'   => $xsetting,
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
            Link::make(__('Back to list'))->icon('icon-arrow-left-circle')->href(route('platform.xsetting.list')),
            Button::make(__('Save'))->icon('icon-check')->method('save'),
            Button::make(__('Remove'))->icon('icon-close')->method('remove')->canSee($this->edit),
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
		
		    Layout::columns([
                'EditSetting' => [
                    XSettingEditLayout::class
                ],
            ]),
		
        ];
    }
    /**
     * @param $request
     * @param XSetting $xsetting
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save($request, XSetting $xsetting)
    {

		$req = $this->request->get('xsetting');

        if ($req['options']['type']=='code') {
            $req['value']=json_decode($req['value'], true);
        }

		$xsetting->updateOrCreate(['key' => $req['key']], $req );
        $xsetting = XSetting::where("key",$req['key'])->first();
        $xsetting->cacheErase($xsetting->key);

        Alert::info(__('Setting was saved'));
        return redirect()->route('platform.xsetting.list');
    }

    /**
     * @param XSetting $xsetting
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
	 
    public function remove(XSetting $xsetting)
    {
        $xsetting->delete();
        Alert::info(__('Setting was removed'));
        return redirect()->route('platform.xsetting.list');
    }
}
