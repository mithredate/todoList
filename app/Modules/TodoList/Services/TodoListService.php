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
use App\Modules\TodoList\Http\ErrorResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Policies\TodoListPolicy;
use App\User;
use Illuminate\Validation\UnauthorizedException;


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
    /**
     * @var TodoListPolicy
     */
    private $policy;
    /**
     * @var ErrorResponse
     */
    private $errorResponse;

    public function __construct(RepositoryContract $repository,
                                CollectionResponse $collectionResponse,
                                ItemResponse $itemResponse,
                                ErrorResponse $errorResponse)
    {
        $this->repository = $repository;
        $this->collectionResponse = $collectionResponse;
        $this->itemResponse = $itemResponse;
        $this->create_href = action('\App\Modules\TodoList\Controllers\TodoListController@index');
        $this->view_single_href = '\App\Modules\TodoList\Controllers\TodoListController@show';
        $this->list_item_href = '\App\Modules\TodoList\Controllers\ListItemController@index';
        $this->errorResponse = $errorResponse;
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
        try {
            $user = func_get_arg(2);
            $this->authorize('update', $user, $id);
            $todoList = $this->repository->update($data, $id);
            return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href, [], $this->getListItemHref($todoList));
        } catch (\Exception $e){
            return $this->prepareErrorResponse($e);
        }
    }

    public function delete($id)
    {
        try {
            $user = func_get_arg(1);
            $this->authorize('delete',$user,$id);
            $this->repository->delete($id);
            return null;
        } catch (\Exception $e){
            return $this->prepareErrorResponse($e);
        }
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

    private function authorize($method, $user, $list_id)
    {
        $list = TodoList::find($list_id);
        if ($user->cant($method, $list)) {
            throw new UnauthorizedException("You are not authorized to $method the resource", 403);
        }
    }

    /**
     * @param $e
     * @return array
     */
    private function prepareErrorResponse(\Exception $e)
    {
        return $this->errorResponse->render($this->create_href, [], null, null, [], [], [
            'title' => get_class($e),
            'code' => $e->getCode(),
            'message' => $e->getMessage()
        ]);
    }


}