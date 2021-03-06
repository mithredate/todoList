<?php

use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Services\TodoListService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\UnauthorizedException;

class TodoListServiceTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $service;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(\App\User::class)->create();

        $this->service = resolve(TodoListService::class,['user' => $this->user]);

    }

    public function testIndexResponse()
    {
        $response = $this->service->index();
        $this->validateResponse($response);
    }

    public function testCreate()
    {
        $newList = factory(TodoList::class)->make([
            'user_id' => $this->user->id
        ]);
        $data = array_only($newList->toArray(),['title','description']);
        $response = $this->service->create($data);
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

    public function testUnauthorizedUpdate()
    {
        $list = factory(TodoList::class)->create();

        $data['title'] = 'modified data';
        try {
            $response = $this->service->update($data, $list->id);
        } catch(UnauthorizedException $e){
            return;
        }

        $this->fail('Unauthorized update on TodoListServiceTest');

    }

    public function testDelete()
    {
        $newList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->service->delete($newList->id);
        $this->assertNull($response);
    }

    public function testUnauthorizedDelete()
    {
        $list = factory(TodoList::class)->create();
        try {
            $response = $this->service->delete($list->id);
        } catch(UnauthorizedException $e){
            return;
        }

        $this->fail('Unauthorized delete on TodoListServiceTest');
    }

    public function testShow(){
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->service->show($list->id);
        $this->validateResponse($response);
        $this->validateResponseLinks($response);
    }

    public function testUnauthorizedShow()
    {
        $list = factory(TodoList::class)->create();

        try {
            $response = $this->service->show($list->id);
        } catch(UnauthorizedException $e){
            return;
        }

        $this->fail('Unauthorized show on TodoListServiceTest');

    }


}
