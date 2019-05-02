<?php
namespace Orchids\XSetting\Http\Screens;

use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Setting;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
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
            $this->description = $xsetting->options['title'];
        }
        //$xsetting = is_null($xsetting) ? new XSetting() : XSetting::where("key",$xsetting)->first();;
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
            Link::name(__('Back to list'))->icon('icon-arrow-left-circle')->link(route('platform.xsetting.list')),
            Link::name(__('Save'))->icon('icon-check')->method('save'),
            Link::name(__('Remove'))->icon('icon-close')->method('remove')->canSee($this->edit),
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
