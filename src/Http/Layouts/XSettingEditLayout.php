<?php
namespace Orchids\XSetting\Http\Layouts;

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
			'key'		=> Field::tag('input')
                ->name('xsetting.key')
                ->required()
				->max(255)
                ->title('Key slug'),
		
			'title'		=> Field::tag('input')
                ->name('xsetting.options.title')
                ->required()
				->max(255)
                ->title('Title'),
			
			'desc'	=> Field::tag('textarea')
                ->name('xsetting.options.desc')
				->row(5)
                ->title('Description'),
				
			'type' => Field::tag('select')
                ->options([
                    'input'    => 'Input',
                    'textarea' => 'Textarea',
                    'picture'  => 'Picture',
                    'code'     => 'CodeEditor (JSON)',
                    'codejs'   => 'CodeEditor (JavaScript)',
                    'tags'     => 'Tags',
                ])
                ->name('xsetting.options.type')
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
				$fields['width'] = Field::tag('input')
                         ->name('xsetting.value.width')
                         ->title('Picture width');
				$fields['height'] = Field::tag('input')
                         ->name('xsetting.value.height')
                         ->title('Picture height');
				$fields['value'] = Field::tag('picture')
						 ->name('xsetting.value.value')
						 ->width($this->query->getContent('xsetting.value.width') ?? 500)
						 ->height($this->query->getContent('xsetting.value.height') ?? 300);
				break;
            case 'code':    
                $fields['value'] = Field::tag($type)
				 ->name('xsetting.value')
                 ->language('json')
                 ->title('Value code');
                 break;
            case 'codejs':    
                $fields['value'] = Field::tag('code')
				 ->name('xsetting.value')
                 ->language('js')
                 ->title('Value code');
                 break;                 
			default:
				$fields['value'] = Field::tag($type)
				 ->name('xsetting.value')
				 ->title('Value');
		}
		return $fields;

    }

}