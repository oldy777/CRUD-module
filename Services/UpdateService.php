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

class UpdateService
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

    public function update($id) : Model
    {
        $item = $this->getItemById($id);

        $values = $this->getFormValues($item);

        $item->update($this->modelValues($values));

        $this->updateRelations($item, $this->relationValues($values));

        return $item;
    }

    public function getFormValues($model)
    {
        $form = $this->formBuilder->create($this->form, ['model'=>$model]);

        $form->redirectIfNotValid();

        return $form->getFieldValues();
    }

    private function updateRelations(Model $item, $values)
    {
        foreach ($values as $name => $value) {
            $item->$name()->sync($value);
        }
    }

    private function getItemById($id)
    {
        return $this->model->findOrFail($id);
    }

}