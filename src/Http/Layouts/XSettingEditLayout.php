<?php
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Tags;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Builder;

class XSettingEditLayout extends Rows
{
    /**
     * @return array
     */
	public function fields(): array
    {
		$fields = [
			'key'		=> Input::make('xsetting.key')
                ->required()
				->max(255)
                ->title('Key slug'),
		
			'title'		=> Input::make('xsetting.options.title')
                ->required()
				->max(255)
                ->title('Title'),
			
			'desc'	=> TextArea::make('xsetting.options.desc')
				->row(5)
                ->title('Description'),
				
			'type' => Select::make('xsetting.options.type')
                ->options([
                    'input'    => 'Input',
                    'textarea' => 'Textarea',
                    'picture'  => 'Picture',
                    'code'     => 'CodeEditor (JSON)',
                    'codejs'   => 'CodeEditor (JavaScript)',
                    'tags'     => 'Tags',
                ])
                ->title('Type'),
        ];

        if (!is_null($this->query->getContent('xsetting.options.type'))) {
            $type = $this->query->getContent('xsetting.options.type');
        } elseif (is_array($this->query->getContent('xsetting.value'))) {
            $type = 'code';
        } else {
            $type = 'input';
        }

		switch ($type) {
		
			case 'picture':
				$fields['width'] = Input::make('xsetting.value.width')
                         ->title('Picture width');
				$fields['height'] = Input::make('xsetting.value.height')
                         ->title('Picture height');
				$fields['value'] = Picture::make('xsetting.value.value')
						 ->width($this->query->getContent('xsetting.value.width') ?? 500)
						 ->height($this->query->getContent('xsetting.value.height') ?? 300);
				break;
            case 'code':    
                $fields['value'] = Code::make('xsetting.value')
                 ->language('json')
                 ->title('Value code');
                 break;
            case 'codejs':    
                $fields['value'] = Code::make('xsetting.value')
                 ->language('js')
                 ->title('Value code');
                 break;
            case 'textarea':
                $fields['value'] = TextArea::make('xsetting.value')
                    ->title('Value');
                    break;
            case 'tags':
                $fields['value'] = Tags::make('xsetting.value')
                    ->title('Value');
                break;
			default:
				$fields['value'] = Input::make('xsetting.value')
				 ->title('Value');
		}
		return $fields;

    }

}