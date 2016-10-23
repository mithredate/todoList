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

    }

    public function store($list, Request $request){
        $response = $this->service->create($request->all(), $list);
        return response()->collectionJson($response, 201);
    }
}
