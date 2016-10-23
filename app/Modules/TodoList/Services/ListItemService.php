<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 1:37 PM
 */

namespace App\Modules\TodoList\Services;


use App\Modules\TodoList\Contracts\ListItemRepository;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\ListItem;
use Illuminate\Http\Request;

class ListItemService
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

    public function __construct(ListItemRepository $repository,
                                CollectionResponse $collectionResponse,
                                ItemResponse $itemResponse,
                                Request $request)
    {

        $this->repository = $repository;
        $this->collectionResponse = $collectionResponse;
        $this->itemResponse = $itemResponse;
        $this->item_href = '\App\Modules\TodoList\Controllers\ListItemController@show';
        $this->create_href = action('\App\Modules\TodoList\Controllers\ListItemController@index',['list' => $request->route('list')]);
    }


    public function create($data, $list_id)
    {
        $response = $this->repository->create($data, $list_id);
        return $this->itemResponse->render($this->create_href,ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }

    public function index($list_id){
        $response = $this->repository->paginate(config('app.pagination_count'));
        return $this->collectionResponse->render($this->create_href,ListItem::$template, $response, $this->item_href, ['list' => $list_id]);
    }
}