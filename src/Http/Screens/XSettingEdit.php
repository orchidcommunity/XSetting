<?php
namespace Orchids\XSetting\Http\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\LayoutFactory;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;

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
            Link::make(__('Back to list'))->icon('arrow-left-circle')->route('platform.xsetting.list'),
            Button::make(__('Save'))->icon('check')->method('save'),
            Button::make(__('Remove'))->icon('close')->method('remove')->canSee($this->edit),
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
            LayoutFactory::columns([
                'EditSetting' => [
                    XSettingEditLayout::class
                ],
            ]),
        ];
    }

    /**
     * @param XSetting $xsetting
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(XSetting $xsetting, Request $request)
    {
		$req = $request->get('xsetting');

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
