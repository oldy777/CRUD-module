<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 19.02.2018
 * Time: 12:29
 */

namespace Modules\Crud\Services;


use Illuminate\Database\Eloquent\Model;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;

class CreateService
{
    use RelationTrait;

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

    public function createForm($route, $data = []) : Form
    {
        return $this->formBuilder->create($this->form, [
            'method' => 'POST',
            'url' => $route,
            'model' => $data
        ]);
    }

    public function store(bool $sortable = false, $additional_fields = []) : Model
    {
        $values = $this->getFormValues($additional_fields);

        if($sortable){
            $values['pos'] = $this->model->max('pos')+1;
        }

        $item = $this->model->create($this->modelValues($values));

        $this->creteModelRelations($item, $this->relationValues($values));

        return $item;
    }

    public function getFormValues($additional_fields)
    {
        $form = $this->formBuilder->create($this->form);

        $form->redirectIfNotValid();

        return array_merge($form->getFieldValues(), $additional_fields);
    }

    private function creteModelRelations(Model $item, $values)
    {
        foreach ($values as $name=>$value) {
            $item->$name()->attach($value);
        }
    }

}