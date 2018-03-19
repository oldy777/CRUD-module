<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 19.02.2018
 * Time: 12:29
 */

namespace Modules\Crud\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilder;

class DestroyService
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model instanceof Model || $model instanceof Relation ? $model : new $model;

    }

    public function destroy($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}