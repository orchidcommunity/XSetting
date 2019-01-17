<?php
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Fields\CodeField;
use Orchid\Screen\Fields\InputField;
use Orchid\Screen\Fields\PictureField;
use Orchid\Screen\Fields\SelectField;
use Orchid\Screen\Fields\TagsField;
use Orchid\Screen\Fields\TextAreaField;
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
			'key'		=> InputField::make('xsetting.key')
                ->required()
				->max(255)
                ->title('Key slug'),
		
			'title'		=> InputField::make('xsetting.options.title')
                ->required()
				->max(255)
                ->title('Title'),
			
			'desc'	=> TextAreaField::make('xsetting.options.desc')
				->row(5)
                ->title('Description'),
				
			'type' => SelectField::make('xsetting.options.type')
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
				$fields['width'] = InputField::make('xsetting.value.width')
                         ->title('Picture width');
				$fields['height'] = InputField::make('xsetting.value.height')
                         ->title('Picture height');
				$fields['value'] = PictureField::make('xsetting.value.value')
						 ->width($this->query->getContent('xsetting.value.width') ?? 500)
						 ->height($this->query->getContent('xsetting.value.height') ?? 300);
				break;
            case 'code':    
                $fields['value'] = CodeField::make('xsetting.value')
                 ->language('json')
                 ->title('Value code');
                 break;
            case 'codejs':    
                $fields['value'] = CodeField::make('xsetting.value')
                 ->language('js')
                 ->title('Value code');
                 break;
            case 'textarea':
                $fields['value'] = TextAreaField::make('xsetting.value')
                    ->title('Value');
                    break;
            case 'tags':
                $fields['value'] = TagsField::make('xsetting.value')
                    ->title('Value');
                break;
			default:
				$fields['value'] = InputField::make('xsetting.value')
				 ->title('Value');
		}
		return $fields;

    }

}