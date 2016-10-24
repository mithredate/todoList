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
use App\Modules\TodoList\Traits\ServiceAuthorization;
use App\User;
use Exception;
use Illuminate\Validation\UnauthorizedException;


class TodoListService implements ControllerServices
{

    use ServiceAuthorization;

    protected $repository;

    protected $create_href;
    protected $view_single_href;
    protected $list_item_href;
    protected $user;
    /**
     * @var CollectionResponse
     */
    private $collectionResponse;
    /**
     * @var ItemResponse
     */
    private $itemResponse;

    /**
     * @var ErrorResponse
     */
    private $errorResponse;

    public function __construct(RepositoryContract $repository,
                                CollectionResponse $collectionResponse,
                                ItemResponse $itemResponse,
                                ErrorResponse $errorResponse,
                                User $user = null)
    {
        $this->repository = $repository;
        $this->collectionResponse = $collectionResponse;
        $this->itemResponse = $itemResponse;
        $this->create_href = action('\App\Modules\TodoList\Controllers\TodoListController@index');
        $this->view_single_href = '\App\Modules\TodoList\Controllers\TodoListController@show';
        $this->list_item_href = '\App\Modules\TodoList\Controllers\ListItemController@index';
        $this->errorResponse = $errorResponse;
        $this->user = $user;
    }


    public function create($data)
    {
        $todoList = $this->repository->create($data, $this->user->id);
        return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href,[],$this->getListItemHref($todoList));
    }

    public function index()
    {
        $todoList = $this->repository->paginate(config('app.pagination_count'), $this->user->id);
        return $this->collectionResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href);

    }

    public function update($data, $id)
    {
        try {
            $this->authorize('update', $this->user, $id, TodoList::class);
            $todoList = $this->repository->update($data, $id);
            return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href, [], $this->getListItemHref($todoList));
        } catch (Exception $e){
            return $this->prepareErrorResponse($this->errorResponse, $e);
        }
    }

    public function delete($id)
    {
        try {
            $this->authorize('delete',$this->user,$id, TodoList::class);
            $this->repository->delete($id);
            return null;
        } catch (Exception $e){
            return $this->prepareErrorResponse($this->errorResponse, $e);
        }
    }

    public function show($id)
    {
        try{
            $this->authorize('view',$this->user, $id, TodoList::class);
            $todoList = $this->repository->getOne($id);
            return $this->itemResponse->render($this->create_href, TodoList::$template, $todoList, $this->view_single_href,[],$this->getListItemHref($todoList));
        } catch (Exception $e){
            return $this->prepareErrorResponse($this->errorResponse, $e);
        }

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