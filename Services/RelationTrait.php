<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 19.02.2018
 * Time: 17:34
 */

namespace Modules\Crud\Services;


trait RelationTrait
{
    private function relationValues($values)
    {
        return array_filter($values, function($name){
            return $this->isRelationField($name);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function modelValues($values)
    {
        return array_filter($values, function($name){
            return !$this->isRelationField($name);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function isRelationField($name)
    {
        return in_array($name, $this->relations);
    }
}