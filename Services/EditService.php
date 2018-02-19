<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 19.02.2018
 * Time: 15:53
 */

namespace Modules\Crud\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Kris\LaravelFormBuilder\FormBuilder;

class EditService
{
    private $model;
    private $formBuilder;
    private $form;
    private $relations;

    public function __construct($model, $form_name, $relations = null, FormBuilder $formBuilder)
    {
        $this->model = $model instanceof Model || $model instanceof Relation ? $model : new $model;
        $this->formBuilder = $formBuilder;
        $this->form = $form_name;
        $this->relations = $relations;
    }

    public function getItemById($id)
    {
        $model = $this->model;

        if($this->relations){
            $model = $this->model->with($this->relations);
        }

        return $model->findOrFail($id);
    }

    public function getEditForm(Model $item, $route)
    {
        $values = $item->toArray();

        if(property_exists($item, 'translatedAttributes')){
            $values = array_merge($values, $item->getTranslationsArray());
        }

        return $this->formBuilder->create($this->form,
            [
                'method' => 'PATCH',
                'url' => $route,
                'model' => $values,
            ]);
    }
}