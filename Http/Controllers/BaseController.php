<?php

namespace Modules\Crud\Http\Controllers;


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Core\Http\Controllers\Controller;
use Modules\Crud\Exceptions\ClassNotSpecifiedException;

abstract class BaseController extends Controller
{

    protected $sortOrder = 'DESC';
    protected $sortValue = 'id';
    protected $isSortable = false;
    protected $action_url_params = [];
    protected $page_size = 25;

    /**
     * model relations name or array of names
     */
    protected $relations = [];

    /**
     * 0 - название списка
     * 1 - название доавления/редактирования
     * @var array
     */
    protected $titles = [];

    /**
     * Templates
     */
    protected $templateIndex = 'crud::common.index';
    protected $templateAdd = 'crud::common.create';
    protected $templateEdit = 'crud::common.edit';


    protected function getModel()
    {
        return $this->getClassName(config('crud.models_folder'), '', 'Не указан класс модели.');

    }

    protected function getForm()
    {
        return $this->getClassName(config('crud.forms_folder'), 'Form', 'Не указан класс формы.');
    }

    protected function getTitle($section = 'index')
    {
        $titles = [
            'index' => $this->titles[0] ?? 'Список',
            'create' => 'Добавление '.($this->titles[1] ?? ''),
            'edit' => 'Редактирование '.($this->titles[1] ?? '')
        ];

        return $titles[$section];
    }

    protected function setPageSize(int $size)
    {
        $this->page_size = $size;
        Session::put('page_size', $this->page_size);
    }

    protected function pageSize()
    {
        if(Session::has('page_size')){
            $this->page_size = Session::get('page_size');
        }

        return $this->page_size;
    }

    protected function getController()
    {
        return '\\'.get_class($this);
    }

    protected function getActionRoute($action)
    {
        return $this->getController().'@'.$action;
    }

    protected function redirectToAction($action, $parameters = [], $status = '302')
    {
        return redirect()->action($this->getActionRoute($action), $parameters, $status);
    }

    private function getClassName($path, $suffix = '', $error_msg = 'Не указан класс')
    {
        $controller = new \ReflectionClass($this);
        $name = Str::singular(str_replace('Controller', '', $controller->getShortName()));
        $namespace = str_replace('Http\\Controllers', '', $controller->getNamespaceName());

        $class = $namespace.$path.'\\'.$name.$suffix;

        if(!class_exists($class)){
            throw new ClassNotSpecifiedException($error_msg);
        }

        return $class;
    }
}
