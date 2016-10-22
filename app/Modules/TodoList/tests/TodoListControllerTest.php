<?php

use App\Modules\TodoList\Models\TodoList;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListControllerTest extends TestCase
{

    use DatabaseTransactions, WithoutMiddleware;

    protected $headers = ['accept' => 'application/json'];

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = User::first();

        $this->actingAs($this->user);
    }


    public function testCreateTodoList()
    {
        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
        $mock->shouldReceive('create')->once()->withAnyArgs()->andReturn(anInstanceOf(User::class));

        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);
//        $this->post('api/v1',$todoList, $this->headers);

        $this->call('POST','api/v1',$todoList,[],[],$this->headers);

        $this->assertResponseStatus(201);

        
    }
}
