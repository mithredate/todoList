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
        $mock->shouldReceive('create')->once()->withAnyArgs()->andReturn(anInstanceOf(TodoList::class));

        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);
//        $this->post('api/v1',$todoList, $this->headers);

        $this->call('POST','api/v1/list',$todoList,[],[],$this->headers);

        $this->assertResponseStatus(201);

        
    }

    public function testEditTodoList(){
        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
        $mock->shouldReceive('update')->once()->withAnyArgs()->andReturn(anInstanceOf(TodoList::class));

        $todoList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $initial = $modified = array_only($todoList->toArray(),['title','description']);
        $modified['title'] = 'Updated todo list title';

        $this->call('PUT','api/v1/list/' . $todoList->id,$modified,[],[],$this->headers);

        $this->assertResponseStatus(200);

    }
}
