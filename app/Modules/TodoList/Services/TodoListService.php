<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 5:11 PM
 */

namespace App\Modules\TodoList\Services;


use App\Modules\TodoList\Contracts\TodoListRepository;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\TodoList;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TodoListService
{

    protected $repository;

    protected $request;

    protected $app;

    protected $create_href;
    protected $view_single_href;
    /**
     * @var CollectionResponse
     */
    private $collectionResponse;
    /**
     * @var ItemResponse
     */
    private $itemResponse;

    public function __construct(TodoListRepository $repository,
                                Request $request,
                                Application $app,
                                CollectionResponse $collectionResponse,
                                ItemResponse $itemResponse)
    {
        $this->app = $app;
        $this->repository = $repository;
        $this->request = $request;
        $this->collectionResponse = $collectionResponse;
        $this->itemResponse = $itemResponse;
        $this->create_href = action('\App\Modules\TodoList\Controllers\TodoListController@index');
        $this->view_single_href = '\App\Modules\TodoList\Controllers\TodoListController@show';
    }

    public function create($data, $user_id)
    {
        $todoList = $this->repository->create($data, $user_id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);
    }

    public function index()
    {
        $todoList = $this->repository->paginate(config('app.pagination_count'));
        return $this->collectionResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);

    }

    public function update($data, $list_id)
    {
        $todoList = $this->repository->update($data, $list_id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return null;
    }

    public function get($list_id)
    {
        $todoList = $this->repository->getOne($list_id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);
    }

}