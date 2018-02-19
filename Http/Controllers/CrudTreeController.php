<?php

namespace Modules\Core\Http\Controllers;


use Caffeinated\Flash\Facades\Flash;
use Illuminate\Http\Request;
use Modules\Crud\Services\CreateService;
use Modules\Crud\Services\CrudService;
use Modules\Crud\Services\CrudTreeTransformer;
use Modules\Crud\Http\Controllers\BaseController;
use Modules\Crud\Services\DestroyService;
use Modules\Crud\Services\EditService;
use Modules\Crud\Services\UpdateService;

abstract class CrudTreeController extends BaseController
{

    /**
     * @var CrudService
     */
    protected $createService;
    protected $editService;
    protected $updateService;
    protected $destroyService;

    public function __construct()
    {
        $this->createService = app()->make(CreateService::class, [
            'model' => $this->getModel(),
            'form_name' => $this->getForm(),
            'relations' => $this->relations,
        ]);

        $this->editService = app()->make(EditService::class, [
            'model' => $this->getModel(),
            'form_name' => $this->getForm(),
            'relations' => $this->relations,
        ]);

        $this->updateService = app()->make(UpdateService::class, [
            'model' => $this->getModel(),
            'form_name' => $this->getForm(),
            'relations' => $this->relations,
        ]);

        $this->destroyService = app()->make(DestroyService::class, [
            'model' => $this->getModel(),
        ]);
    }

    /**
     * Templates
     */
    protected $templateIndex = 'core::common.tree.index';


    public function index()
    {
        return view($this->templateIndex, [
            'title' => $this->getTitle(),
            'controller' => $this->getController(),
        ]);
    }

    public function list(Request $request, CrudTreeTransformer $transformer)
    {
        if(!$request->isXmlHttpRequest()){
            abort(404);
        }

        $model = $this->getModel();
        $items = $model::whereNull('parent_id')->with('childrenRecursive')->get();

        return $transformer->transform($items);
    }

    public function create($parent_id = null)
    {
        $route = action($this->getActionRoute('store'), $parent_id);

        $form = $this->createService->createForm($route);

        return view($this->templateAdd, [
            'form' => $form,
            'title' => $this->getTitle('create'),
            'module_title' => $this->getTitle('index'),
            'controller' => $this->getController(),
        ]);
    }

    public function store($parent_id = null)
    {
        $this->createService->store($this->isSortable, ['parent_id' => $parent_id]);

        Flash::success('Запись добавлена.');

        return $this->redirectToAction('index', $this->action_url_params);
    }

    public function edit($id)
    {
        $item = $this->editService->getItemById($id);
        $route = action($this->getActionRoute('update'), array_merge(['id' => $id], $this->action_url_params));
        $form = $this->editServicee->getEditForm($item, $route);

        return view($this->templateEdit, [
            'form' => $form,
            'title' => $this->getTitle('edit'),
            'module_title' => $this->getTitle('index'),
            'controller' => $this->getController(),
            'item' => $item,
        ]);
    }

    public function update($id)
    {
        $this->updateService->update($id);

        Flash::success('Запись обновлена.');

        return $this->redirectToAction('index', $this->action_url_params);
    }

    public function destroy($id)
    {
        $this->destroyService->destroy($id);

        return ['sux' => 1];
    }

}
