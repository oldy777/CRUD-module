<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 23.11.2017
 * Time: 17:03
 */

namespace Modules\Crud\Contracts;


trait TreeModelTrait
{
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }
}