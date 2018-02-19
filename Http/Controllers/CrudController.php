<?php

namespace Modules\Crud\Http\Controllers;


use Caffeinated\Flash\Facades\Flash;
use Illuminate\Support\Facades\Session;
use Modules\Crud\Services\CreateService;
use Modules\Crud\Services\CrudService;
use Modules\Crud\Services\DestroyService;
use Modules\Crud\Services\EditService;
use Modules\Crud\Services\IndexService;
use Modules\Crud\Services\UpdateService;

abstract class CrudController extends BaseController
{
    /**
     * @var CrudService
     */
    protected $indexService;
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

        $this->indexService = app()->make(IndexService::class, [
            'model' => $this->getModel(),
            'sortable' => $this->isSortable
        ]);

    }

    protected function listFields()
    {
        return [
            'title' => [
                'title' => 'Название',
                'link' =>''
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $request = request();
        if($request->query->has('page_size')){
            $this->setPageSize(request()->query->getInt('page_size', $this->page_size));
        }

        $items = $this->indexService->getItems($this->pageSize());

        return view($this->templateIndex, [
                'items' => $items,
                'title' => $this->getTitle(),
                'controller' => $this->getController(),
                'fields' => $this->listFields(),
                'sortable' => $this->isSortable
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route = action($this->getActionRoute('store'), $this->action_url_params);

        $form = $this->createService->createForm($route);

        return view($this->templateAdd, [
            'form' => $form,
            'title' => $this->getTitle('create'),
            'module_title' => $this->getTitle('index'),
            'controller' => $this->getController(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->createService->store($this->isSortable);

        Flash::success('Запись добавлена.');

        return $this->redirectToAction('index', $this->action_url_params);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->redirectToAction('edit', array_merge(['id' => $id], $this->action_url_params));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route = action($this->getActionRoute('update'), array_merge(['id' => $id], $this->action_url_params));

        $item = $this->editService->getItemById($id);
        $form = $this->editService->getEditForm($item, $route);

        return view($this->templateEdit, [
            'form' => $form,
            'title' => $this->getTitle('edit'),
            'module_title' => $this->getTitle('index'),
            'controller' => $this->getController(),
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->updateService->update($id);

        Flash::success('Запись обновлена.');

        return $this->redirectToAction('index', $this->action_url_params);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->destroyService->destroy($id);

        Flash::success('Запись удалена.');

        return $this->redirectToAction('index', $this->action_url_params);
    }

}
