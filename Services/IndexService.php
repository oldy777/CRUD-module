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

class IndexService
{
    private $model;
    private $sortable;

    public function __construct($model, $sortable)
    {
        $this->model = $model instanceof Model || $model instanceof Relation ? $model : new $model;
        $this->sortable = $sortable;
    }

    public function getItems($amount, $sort_value = 'id', $sort_order = 'DESC')
    {
        if($this->sortable){
            $model = $this->model->orderBy('pos', 'ASC');
        }else{
            $model = $this->model->orderBy($sort_value, $sort_order);
        }

        return $model->paginate($amount);
    }
}