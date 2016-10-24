<?php

use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Services\TodoListService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListServiceTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $service;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(\App\User::class)->create();

        $this->service = resolve(TodoListService::class);
    }

    public function testIndexResponse()
    {
        $response = $this->service->index($this->user->id);
        $this->validateResponse($response);
    }

    public function testCreate()
    {
        $newList = factory(TodoList::class)->make([
            'user_id' => $this->user->id
        ]);
        $data = array_only($newList->toArray(),['title','description']);
        $response = $this->service->create($data, $newList->user_id);
        $this->validateResponse($response);
        $this->validateResponseLinks($response);
    }

    public function testUpdate()
    {
        $newList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $data = array_only($newList->toArray(),['title','description']);
        $data['title'] = 'modified data';
        $response = $this->service->update($data, $newList->id);
        $this->validateResponse($response);
        $this->validateResponseLinks($response);
    }

    public function testDelete()
    {
        $newList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->service->delete($newList->id);
        $this->assertNull($response);
    }

    public function testShow(){
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->service->show($list->id);
        $this->validateResponse($response);
        $this->validateResponseLinks($response);
    }


    
}
