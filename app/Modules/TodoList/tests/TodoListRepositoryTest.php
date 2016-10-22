<?php

use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Repositories\EloquentTodoListRepository;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListRepositoryTest extends TestCase
{


    use DatabaseTransactions,
        WithoutMiddleware;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = User::first();
    }

    public function testCreateTodoList()
    {


        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);

        $repository = new EloquentTodoListRepository();

        $todo = $repository->create($todoList, $this->user->id);

        $this->assertInstanceOf('App\Modules\TodoList\Models\TodoList', $todo);

        $this->seeInDatabase('todo_list',$todoList);
    }
}
