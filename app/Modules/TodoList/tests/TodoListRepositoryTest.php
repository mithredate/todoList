<?php

use App\Modules\TodoList\Contracts\TodoListRepository;
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

        $this->user = factory(User::class)->create();

        $this->repository = resolve(TodoListRepository::class);
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

    public function testNullDescriptionOnEmptyString()
    {
        $todoList = [
            'title' => str_random(200),
            'description' => ''
        ];
        $todo = $this->repository->create($todoList, $this->user->id);
        $this->assertNull($todo->description);
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

    public function testDeleteTodoList()
    {
        $todoList = factory(TodoList::class)->create();
        $deleted = $this->repository->delete($todoList->id);
        $this->assertTrue($deleted);
        $this->dontSeeInDatabase('todo_list',$todoList->toArray());
    }

    public function testGetAllTodoList(){
        $todoList = factory(TodoList::class)->times(50)->create();
        $list = $this->repository->getAll();
        $this->assertGreaterThan(50, $list->count());
    }

    public function testPaginateTodoList(){
        $todoList = factory(TodoList::class)->times(50)->create();
        $list = $this->repository->paginate(10);
        $this->assertEquals(10, $list->count());
    }
}
