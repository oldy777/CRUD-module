# Crud
Laravel Crud Module

## Install
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

## Usage
������ ����� ������������� ����� ������� ��� �������� �������� CRUD �� ������ ������ � �����. 

� ������ ������� ���������� ������� ������. �� ��������� ������ ����� �������� � �������� **Entity**, 
�� ��� ������������ ����� �������� � ������� 
```$xslt
'models_folder' => 'Entities',
```

����� ���������� ������� ����� ����� ��� ������. �� ��������� ����� ����� �������� � �������� **Http\\Forms**, 
��� ������������ ���� ����� �������� � �������
```
'forms_folder' => 'Http\\Forms'
```

��������� ������ ����� �����
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
                'label' => '��������'
            ])
            ->add('text', 'textarea', [
                'label' => '��������'
            ])

            ->add('users',  'entity', [
                'label' => '������������',
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

**����� ��������:** ��� �� ������ � ����� ������������ ������������� ��� ������ ����������� 
��������� �������.
- �������� ������ - ��� �������� ����������� � ������������ �����
- �������� ����� - ��� �������� ����������� � ������������ ����� + �������� Form. �������� `GroupForm`

������ ������� ����������. ����� ����������� ����������� �� ������������� ������ `CrudController`
```php
namespace Modules\Users\Http\Controllers;


use Modules\Crud\Http\Controllers\CrudController;

class GroupsController extends CrudController
{
    protected $titles = ['������ �������', '������'];

    protected $relations = ['users'];

}

```

`$titles` - �������� ������ ��������, 1 - ��� ������, 2- ��� ����������/��������������

`$relations` - �������� ������ �� �������, ������� ���������� ����������/���������

����� ����� ����������� ����
```php
Route::resource('/group', Modules\Users\Http\Controllers\GroupsController);
```

� CRUD �����.

