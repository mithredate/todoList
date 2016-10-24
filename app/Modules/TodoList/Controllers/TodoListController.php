<?php

namespace App\Modules\TodoList\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TodoList\Contracts\ControllerServices;
use App\Modules\TodoList\Requests\CreateTodoListRequest;
use App\Modules\TodoList\Requests\UpdateTodoListRequest;
use App\Modules\TodoList\Services\TodoListService;
use Illuminate\Http\Request;


class TodoListController extends Controller
{

    protected $service;

    public function __construct(ControllerServices $service)
    {
        $this->service = $service;
//        $this->service->setUser($request->user());
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->service->index($request->user()->id);

        return response()->collectionJson($response, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTodoListRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTodoListRequest $request)
    {
        $response = $this->service->create($request->only([
            'title','description'
        ]), $request->user()->id);
        return response()->collectionJson($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->service->show($id);
        return response()->collectionJson($response, 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTodoListRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoListRequest $request, $id)
    {
        $response = $this->service->update($request->all(), $id, $request->user());
        return response()->collectionJson($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $response = $this->service->delete($id, $request->user());
        return response()->collectionJson($response,204);
    }

   
}
