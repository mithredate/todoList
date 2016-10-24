<?php

namespace App\Modules\TodoList\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TodoList\Services\ListItemService;
use Illuminate\Http\Request;

use App\Http\Requests;

class ListItemController extends Controller
{
    /**
     * @var ListItemService
     */
    private $service;

    public function __construct(ListItemService $service)
    {

        $this->service = $service;
    }

    public function index($list){
        $response = $this->service->index($list);
        return response()->collectionJson($response, 200);
    }

    public function store($list, Request $request){
        $response = $this->service->create($request->all(), $list);
        return response()->collectionJson($response, 201);
    }

    public function update($list, $items, Request $request)
    {
        $response = $this->service->update($request->all(), $items);
        return response()->collectionJson($response, 200);
    }
}
