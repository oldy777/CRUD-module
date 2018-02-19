# Crud
Laravel Crud Module

##Install
Execute the following command in your terminal

```bash
composer require oldy/crud-module
```

Or add this line in your composer.json file

```bash
require": {
...
"oldy/crud-module" : "dev-master"
}
```

And execute the following command in your terminal

```bash
composer update
```

##Usage
Данный пакет предоставляет общие функции для быстрого создания CRUD на основе модели и формы. 

В первую очередь необходимо создать модель. По умолчанию модель будет искаться в каталоге **Entity**, 
но это расположение можно изменить в конфиге 
```$xslt
'models_folder' => 'Entities',
```

Далее необходимо создать класс формы для вывода. По умолчанию форма будет искаться в каталоге **Http\\Forms**, 
это расположение тоже можно поменять в конфиге
```
'forms_folder' => 'Http\\Forms'
```

Небольшой пример клсса формы
```php
namespace Modules\Users\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Users\Entities\User;

class GroupForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('title', 'text', [
                'rules' => 'required|min:3',
                'label' => 'Название'
            ])
            ->add('text', 'textarea', [
                'label' => 'Описание'
            ])

            ->add('users',  'entity', [
                'label' => 'Пользователи',
                'class' => User::class,
                'multiple' => true,
                'selected' => function ($data) {
                    return $data ? array_pluck($data, 'id') : [];
                },
                'attr' => [
                    'class' => 'multi-select'
                ]
            ])
        ;

    }

}
```

**Важно заметить:** что бы модели и формы подгружались автоматически они должны именоваться 
следующим образом.
- Название модели - это название контроллера в единственном числе
- Название формы - это Название контроллера в единственном числе + постфикс Form. Например `GroupForm`

Теперь создаем контроллер. Класс контроллера наследуются от родительского класса `CrudController`
```php
namespace Modules\Users\Http\Controllers;


use Modules\Crud\Http\Controllers\CrudController;

class GroupsController extends CrudController
{
    protected $titles = ['Группы доступа', 'группы'];

    protected $relations = ['users'];

}

```

`$titles` - содержит массив названий, 1 - для списка, 2- для добавления/редактирования

`$relations` - содержит массив со связями, которые необходимо подгружать/обновлять

После этого прописываем роут
```php
Route::resource('/group', Modules\Users\Http\Controllers\GroupsController);
```

И CRUD готов.

