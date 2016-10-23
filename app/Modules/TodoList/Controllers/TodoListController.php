<?php

namespace App\Modules\TodoList\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\TodoList\Requests\CreateTodoListRequest;
use App\Modules\TodoList\Requests\UpdateTodoListRequest;
use App\Modules\TodoList\Services\TodoListService;
use Illuminate\Http\Request;


class TodoListController extends Controller
{

    protected $service;

    public function __construct(TodoListService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->service->index();

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
        $response = $this->service->get($id);
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
        $response = $this->service->update($request->all(), $id);
        return response()->collectionJson($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->service->delete($id);
        return response()->collectionJson($response,204);
    }

   
}
