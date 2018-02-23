<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 23.11.2017
 * Time: 17:02
 */

namespace Modules\Crud\Contracts;


interface TreeModelInterface
{
    public function children();
    public function childrenRecursive();
    public function parent();
    public function parentRecursive();

    
}