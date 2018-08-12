## Как сделать пакет (плагин) в Orchid шаг за шагом. 
 
В данном уроке научимся создавать плагины для Orchid, отличае плагинов от проекта, в том что его можно легко подключать в другие проекты.

Наш плагин будет отображать [настройки](https://orchid.software/ru/docs/settings) Orchid, а также создаст возможность их редактировать.
![](https://github.com/orchidcommunity/XSetting/blob/master/docs/imgs/create_plugin1.gif)

Настройки легко выводятся в шаблонизаторе blade вот таким кодом ` {{setting('phone')}} `.


### Директория и подключение плагина.
Для начала нужно создать директорию где будут содержаться наши проекты.

1. В корневой директории проекта создаем директорию package.  

2. В директории package создадим папку нашего проекта. - Например XSetting. 

В файле composer.json добавим пути к нашему проекту  
```
    "repositories": [ 
    { 
        "packagist.org": false, 
        "type": "path", 
        "url": "/home/youproject/package/xsetting" 
    }, 
```
Теперь при команде `composer require "orchids/xsetting"` композер будет искать проект также в нашей директории. 
Выполнив эти действия также можно создать плагин не только под Orchid но и для любого проекта под Laravel.
Конечно если вы подключите свой плагин к packagist.org то эти действия не понадобятся.

### Создание плагина.
Создадим структуру пакета 

```
database/ migrations/2018_08_07_000000_create_options_for_settings_table.php
routes/route.php
src/Models/XSetting.php
src/Providers/XSettingProvider.php 
src/Http/Screens/XSettingList.php 
src/Http/Screens/XSettingEdit.php 
src/Http/Layouts/XSettingListLayout.php 
src/Http/Layouts/XSettingEditLayout.php 
Composer.json 
```

Приступаем к разработке расширения.


#### 1. Сначала создадим файл composer.json для Композера 
```
{
  "name": "orchids/xsetting",
  "description": "Extension setting package for Orchid Platform",
  "type": "library",
  "keywords": [
    "Orchid",
    "XSetting"
  ],
  "license": "MIT",
  "require": {
    "orchid/platform":"dev-develop"
  },
  "autoload": {
    "psr-4": {
      "Orchids\\XSetting\\": "src/"
    }
  },
  "extra": {
    "laravel": {
      "providers": [
        "Orchids\\XSetting\\Providers\\XSettingProvider"
      ]
    }
  },
  "minimum-stability": "stable",
  "prefer-stable": true
}
```
Расшифровка основных параметров
` "name": "orchids/xsetting", ` - при выполнении команды в консоли `composer require "orchids/xsetting"` композер обработает этот файл.

` "require": {"orchid/platform":"dev-develop"} ` - необходимые зависимости для установки пакета. 

`"psr-4": {"Orchids\\XSetting\\": "src/"}` - все подключаемые классы у которых путь начинается с `Orchids\XSetting` будут искатся в этом пакете.

`"laravel": {"providers": [ "Orchids\\XSetting\\Providers\\XSettingProvider" ]} ` - После установки запустит провайдер по пути `src/Providers/XSettingProvider.php`

#### 2. Теперь создадим класс провайдера который добавит маршрутизацию, возможность давать пользователям доступ к данному пакету, и добавит пункт нашего плагина в меню. 
Создадим файл `src/Providers/XSettingProvider.php`
```
namespace Orchids\XSetting\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Orchid\Platform\Dashboard;

class XSettingProvider extends ServiceProvider
{
    public function boot(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
        /* 
        * Добавление разрешения доступа для пользователей и ролей. 
        */
        $this->dashboard->registerPermissions([ trans('platform::permission.main.systems') => [
                ['slug' => 'platform.systems.xsetting', 'description' => 'Edit settings']]); 
		
        /* 
        * Подключение файла роутинга 
        */	
        $this->loadRoutesFrom(realpath(__DIR__.'/../../routes/route.php')); 
    
        /*
        *Подключение класса добавления пункта меню 
        */	
        View::composer('platform::container.systems.index', MenuComposer::class);
        
        /* 
        * Подключение файлов миграциии для добавления их в базу данных нужно выполнить `php artisan migrate` 
        */	
        $this->loadMigrationsFrom(realpath(__DIR__.'/../../database/migrations'));
    }
}
```

#### 3. Для начала создадим файл роутинга, который будет определять какой экран отвечает за определенный маршрут.
Создадим файл routes/route.php

```
Route::domain((string) config('platform.domain'))     //Загружает из конфига домен админки
    ->prefix(Dashboard::prefix('/systems'))	      //Загружает из конфига префикс админки и добавляет /systems
    ->middleware(config('platform.middleware.private'))
    ->namespace('Orchids\XSetting\Http\Screens')	//Путь к классам обработчикам пути - экранам
    ->group(function (\Illuminate\Routing\Router $router, $path='platform.xsetting.') {
        $router->screen('xsetting/{xsetting}/edit', 'XSettingEdit',$path.'edit');
        $router->screen('xsetting/create', 'XSettingEdit',$path.'create');
        $router->screen('xsetting', 'XSettingList',$path.'list'); 
    });
```
Теперь при генерации пути `route('platform.xsetting.list')` в браузере будет сгенерирован примерно такой адрес  `http://yousite.name/dashboard/systems/xsetting`, а при переходе на него роутинг запустить файл `src\Http\Screens\XSettingList.php`

#### 4. Добавим в меню пункт настроек пункт с генерацией пути к `route('platform.xsetting.list')` (например в меню "Система"), для этого создадим файл ` src/Providers/MenuComposer.php `
```
namespace Orchids\XSetting\Providers;

use Orchid\Platform\Dashboard;
class MenuComposer
{
    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function compose()
    {
        $this->dashboard->menu
            ->add('CMS', [
                'slug'       => 'XSetting',      //Уникальная строка содержащая только безопасные символы
                'icon'       => 'icon-settings', //CSS код для графической иконки
                'route'      => route('platform.xsetting.list'),  //Путь, route() или ссылка
                'label'      => 'Setting configuration',     // Название меню
                'permission' => 'platform.systems.xsetting', //Какими правами должен обладать пользователь
                'sort'       => 10,               //Сортировка элементов меню 1/2/3/4
            ]);
    }
}
```


#### 5. Теперь нужно добавить в базу данных столбец `options` содержащий дополнительные параметры данного ключа, для этого создаим файл миграции баз даныых 
`database/migrations/2018_08_07_000000_create_options_for_settings_table.php` содержащий 
```
public function up()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->jsonb('options');
    });
}
public function down()
{
    Schema::dropIfExists('settings', function (Blueprint $table) {
        $table->dropColumn('options');
    });
}
```


#### 6. Также нужно создать модель которая описывает связи с базой данных, создадим файл src/Models/XSetting.php
```
namespace Orchids\XSetting\Models;

use Illuminate\Support\Facades\Cache;
use Orchid\Setting\Setting;
use Orchid\Platform\Traits\MultiLanguage;

class XSetting extends Setting
{
    use MultiLanguage;
    
    protected $fillable = ['key','value','options' ];	
    
    protected $casts = [
        'key' =>'string',
        'value' => 'array',
        'options' => 'array',
    ];	
}
```

#### 7. Теперь осталось добавить экраны [Screens](https://orchid.software/ru/docs/screens) и макеты [Layouts](https://orchid.software/ru/docs/layouts)
Добавим экран списка всех настроек, для этого создадим файл src/Http/Screens/XSettingList.php 
```
namespace Orchids\XSetting\Http\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Layouts;
use Orchid\Screen\Link;

use Orchids\XSetting\Models\XSetting;
use Orchids\XSetting\Http\Layouts\XSettingListLayout;

class XSettingList extends Screen
{
    public $name = 'Setting List';
    public $description = 'List all settings';

    public function query() : array
    {
        return [
            'settings' => XSetting::paginate(30)  //Переменная `settings` будет обработана в макете.
        ];
    }

    public function layout() : array
    {
        return [
            XSettingListLayout::class,   //Класс макета.
        ];
    }
    
    public function commandBar() : array
    {
        return [
            Link::name('Create a new setting')->method('create'),  //Добавить в верхнее меню пункт добавления настройки 
								   //запустит функцию create()
        ];
    }

     public function create()
    {
        return redirect()->route('platform.xsetting.create'); 
    }
}
```
Подробнее про создание экранов [ссылка](https://orchid.software/ru/docs/screens)

#### 8. Добавим макет вывода списка всех настроек, для этого создадим файл src/Http/Layouts/XSettingListLayout.php 

```
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\TD;

class XSettingListLayout extends Table
{
    public $data = 'settings';
    public function fields() : array
    {
        return  [
            TD::set('key','Key')
                ->setRender(function ($shortvar) {
                    return '<a href="' . route('platform.blogcms.shortvar.edit',
                        $shortvar->key) . '">' . $shortvar->key . '</a>';
                }),
            TD::set('options.title', 'Name')
                ->setRender(function ($shortvar) {
                    return $shortvar->options['title'];
                }),
            TD::set('value','Value')
                ->setRender(function ($xsetting) {
                     if (is_array($xsetting->value)) {
                        return str_limit(htmlspecialchars(json_encode($xsetting->value)), 50);
                     }
                     return str_limit(htmlspecialchars($xsetting->value), 50);
				}),
        ];
    }
}
```
Подробнее про создание макетов [ссылка](https://orchid.software/ru/docs/layouts)

#### 9. Добавим экран создания и редактирования настройки, для этого создадим файл src/Http/Screens/XSettingEdit.php
```
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
	
    public $name = 'Setting edit';
    public $description = 'Edit setting';

    public function query($xsetting = null) : array
    {
        $xsetting = is_null($xsetting) ? new XSetting() : XSetting::where("key",$xsetting)->first();;
        return [
            'xsetting'   => $xsetting,   //Переменная `xsetting` будет обработана в макете.
        ];
    }

    public function layout() : array
    {
        return [
            Layouts::columns([
                'EditSetting' => [
                    XSettingEditLayout::class   //Макет который обработает даннные
                ],
            ]),
        ];
    }

    public function commandBar() : array
    {
        return [				
            Link::name('Save')->method('save'),   //Добавить в верхнее меню пункт сохранения настройки обработает функция `save`
            Link::name('Remove')->method('remove'), //Добавить в верхнее меню пункт удаления настройки обработает функция `remove`
        ];
    }

    public function save($request, XSetting $xsetting)   // Функция сохранения настройки
    {

        $req = $this->request->get('xsetting');   // Заполненные данные 'xsetting' "вернуться" из макета

        $xsetting->updateOrCreate(['key' => $req['key']], $req );

        Alert::info('Setting was saved');
        return back();
    }
	 
    public function remove($request, XSetting $xsetting)  // Функция удаления настройки
    {
        $xsetting->where('id',$request)->delete();
        Alert::info('Setting was removed');
        return back();
    }
}
```
#### 10. Осталось добавить макет редактирования настроек, создадим ` src/Http/Layouts/XSettingEditLayout.php `
```
namespace Orchids\XSetting\Http\Layouts;

use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Field;
use Orchid\Screen\Fields\Builder;

class XSettingEditLayout extends Rows
{
	public function fields(): array
    {
        return [
            Field::tag('input')				//Поле input
                ->name('xsetting.key')		
                ->required()				//Обязательное
                ->max(255)
                ->title('Key slug'),		//Заголовок
            Field::tag('input')
                ->name('xsetting.options.title')
                ->required()
                ->max(255)
                ->title('Title'),
            Field::tag('textarea')
                ->name('xsetting.options.desc')
                ->row(5)
                ->title('Description'),
            Field::tag('textarea')
                ->name('xsetting.value')
                ->title('Value'),
        ];
	}
}
```
Подробнее про поля [ссылка](https://orchid.software/ru/docs/field)

#### 11. Установка 
- Запускаем в консоли `composer require "orchids/xsetting"`
- Применим миграции `php artisan migrate`
- В админке проставим доступ пользователю.

#### Upadate from 12-08-18 
В плагин была добавлена возможность выбора типа хранимых данных:
- Input - строка
- Textarea - Текст
- Picture - Изображение (Например логотип компании)
- CodeEditor (JSON) - любой массив в JSON виде.
- CodeEditor (JavaScript) - JavaScript, HTML код (Например код гугл аналитики).
- Tags - список слов, теги (Например ключевые слова в `meta name="keywords"`).
![](https://github.com/orchidcommunity/XSetting/blob/master/docs/imgs/create_plugin1.gif)
