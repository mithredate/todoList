<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 5:11 PM
 */

namespace App\Modules\TodoList\Services;


use App\Modules\TodoList\Contracts\ControllerServices;
use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\TodoList;


class TodoListService implements ControllerServices
{

    protected $repository;

    protected $create_href;
    protected $view_single_href;
    protected $list_item_href;
    /**
     * @var CollectionResponse
     */
    private $collectionResponse;
    /**
     * @var ItemResponse
     */
    private $itemResponse;

    public function __construct(RepositoryContract $repository,
                                CollectionResponse $collectionResponse,
                                ItemResponse $itemResponse)
    {
        $this->repository = $repository;
        $this->collectionResponse = $collectionResponse;
        $this->itemResponse = $itemResponse;
        $this->create_href = action('\App\Modules\TodoList\Controllers\TodoListController@index');
        $this->view_single_href = '\App\Modules\TodoList\Controllers\TodoListController@show';
        $this->list_item_href = '\App\Modules\TodoList\Controllers\ListItemController@index';
    }

    public function create($data)
    {
        $user_id = func_get_arg(1);
        $todoList = $this->repository->create($data, $user_id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href,[],$this->getListItemHref($todoList));
    }

    public function index()
    {
        $user_id = func_get_arg(0);
        $todoList = $this->repository->paginate(config('app.pagination_count'), $user_id);
        return $this->collectionResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);

    }

    public function update($data, $id)
    {
        $todoList = $this->repository->update($data, $id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href,[],$this->getListItemHref($todoList));
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return null;
    }

    public function show($id)
    {
        $todoList = $this->repository->getOne($id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href,[],$this->getListItemHref($todoList));
    }

    /**
     * @param $todoList
     * @return array
     */
    private function getListItemHref($todoList)
    {
        $links = [
            ['rel' => 'items', 'href' => action($this->list_item_href, ['list' => $todoList->id]), 'render' => 'link']
        ];
        return $links;
    }

}