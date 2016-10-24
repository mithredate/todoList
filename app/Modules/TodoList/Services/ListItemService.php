<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 1:37 PM
 */

namespace App\Modules\TodoList\Services;


use App\Modules\TodoList\Contracts\ControllerServices;
use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\ListItem;

class ListItemService implements ControllerServices
{
    protected $create_href;
    protected $item_href;
    /**
     * @var ListItemRepository
     */
    private $repository;
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
        $this->item_href = '\App\Modules\TodoList\Controllers\ListItemController@show';
    }


    public function create($data)
    {
        $list_id = func_get_arg(1);
        $response = $this->repository->create($data, $list_id);
        return $this->itemResponse->render($this->getCreateHref($list_id),ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }

    public function index(){
        $list_id = func_get_arg(0);
        $response = $this->repository->paginate(config('app.pagination_count'), $list_id);
        return $this->collectionResponse->render($this->getCreateHref($list_id),ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }

    public function show($id)
    {
        $response = $this->repository->getOne($id);
        $list_id = $response->todoList->id;
        return $this->itemResponse->render($this->getCreateHref($list_id),ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }

    public function update($data, $id)
    {
        $response = $this->repository->update($data, $id);
        $list_id = $response->todoList->id;
        return $this->itemResponse->render($this->getCreateHref($list_id),ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return null;
    }

    /**
     * @param $list_id
     * @return mixed
     */
    private function getCreateHref($list_id)
    {
        return action('\App\Modules\TodoList\Controllers\ListItemController@index', ['list' => $list_id]);
    }
}