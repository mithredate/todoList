<?php

use App\Modules\TodoList\Models\TodoList;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListServiceTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = $this->app['\App\Modules\TodoList\Services\TodoListService'];
    }

    public function testIndexResponse()
    {
        $response = $this->service->index();
        $this->validate($response);
    }

    public function testCreate()
    {
        $newList = factory(TodoList::class)->make();
        $data = array_only($newList->toArray(),['title','description']);
        $response = $this->service->create($data, $newList->user_id);
        $this->validate($response);
        $this->validateLinks($response);
    }

    public function testUpdate()
    {
        $newList = factory(TodoList::class)->create();
        $data = array_only($newList->toArray(),['title','description']);
        $data['title'] = 'modified data';
        $response = $this->service->update($data, $newList->id);
        $this->validate($response);
        $this->validateLinks($response);
    }

    public function testDelete()
    {
        $newList = factory(TodoList::class)->create();
        $response = $this->service->delete($newList->id);
        $this->assertNull($response);
    }

    public function testShow(){
        $list = factory(TodoList::class)->create();
        $response = $this->service->get($list->id);
        $this->validate($response);
        $this->validateLinks($response);
    }

    /**
     * @param $response
     */
    private function validate($response){
        $this->assertArrayHasKey('collection',$response);
        $this->assertArrayHasKey('version',$response['collection']);
        $this->assertArrayHasKey('href',$response['collection']);
    }

    /**
     * @param $response
     */
    private function validateLinks($response)
    {
        $this->assertArrayHasKey('links', $response['collection']);
    }
}
