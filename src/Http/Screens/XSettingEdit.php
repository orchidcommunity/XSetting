<?php
namespace Orchids\XSetting\Http\Screens;

use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Setting;
use Orchid\Screen\Layouts;
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
     * Query data
     *
     * @param XSetting $xsetting
     *
     * @return array
     */
    public function query($xsetting = null) : array
    {
        $xsetting = is_null($xsetting) ? new XSetting() : XSetting::where("key",$xsetting)->first();;
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
            Link::name('Save')->method('save'),
            Link::name('Remove')->method('remove'),
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
		
		    Layouts::columns([
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

        Alert::info('Setting was saved');
        return redirect()->route('platform.xsetting.list');
    }
    /**
     * @param $request
     * @param XSetting $xsetting
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
	 
    public function remove($request, XSetting $xsetting)
    {
		$xsetting->where('id',$request)->delete();
        Alert::info('Setting was removed');
        return redirect()->route('platform.xsetting.list');
    }
}