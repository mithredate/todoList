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

    protected $repository;

    protected function setUp()
    {
        parent::setUp();

        $this->user = User::first();

        $this->repository = new EloquentTodoListRepository();
    }

    public function testCreateTodoList()
    {


        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);


        $todo = $this->repository->create($todoList, $this->user->id);

        $this->assertInstanceOf('App\Modules\TodoList\Models\TodoList', $todo);

        $this->seeInDatabase('todo_list',$todoList);
    }

    public function testEditTodoList()
    {
        $todoList = factory(TodoList::class)->create();
        $initial = $modified = array_only($todoList->toArray(),['title','description']);
        $modified['title'] = 'Updated todo list title';
        $todo = $this->repository->update($modified, $todoList->id);
        $this->assertInstanceOf('App\Modules\TodoList\Models\TodoList',$todo);
        $this->seeInDatabase('todo_list',$modified);
        $this->dontSeeInDatabase('todo_list',$initial);
    }
}
