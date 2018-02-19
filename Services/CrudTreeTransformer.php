<?php
/**
 * Created by Developer.
 * User: Dmitry S.
 * Date: 23.11.2017
 * Time: 17:19
 */

namespace Modules\Crud\Services;


use Illuminate\Support\Collection;
use Modules\Crud\Contracts\TreeModelInterface;

class CrudTreeTransformer
{
    public function transform(Collection $services) : array
    {
        $ret = [];

        foreach ($services as $k => $service) {
            $ret[$k] = $this->addItem($service);
        }

        return $ret;

    }


    private function addItem(TreeModelInterface $service)
    {
        $ret = [];

        $ret['text'] = $service->title;
        $ret['children'] = $this->transform($service->childrenRecursive);
        $ret['state'] = array('opened'=>true);
        $ret['li_attr'] = array(
            'nodeid'=>$service->id,
        );

        return $ret;
    }
}