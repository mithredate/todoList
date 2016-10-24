<?php

use App\Modules\TodoList\Contracts\RepositoryContract;
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

        $this->repository = resolve(EloquentTodoListRepository::class);
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
        factory(TodoList::class)->times(50)->create();
        $todoList = factory(TodoList::class)->times(10)->create([
            'user_id' => $this->user->id
        ]);
        $list = $this->repository->getAll($this->user->id);
        $this->assertEquals(10, $list->count());
    }

    public function testPaginateTodoList(){
        factory(TodoList::class)->times(20)->create();
        $todoList = factory(TodoList::class)->times(30)->create([
            'user_id' => $this->user->id
        ]);
        $list = $this->repository->paginate(10, $this->user->id);
        $this->assertEquals(10, $list->count());
        $this->assertEquals(30, $list->total());
    }

    public function testGetOne()
    {
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $list->load('user');

        $response = $this->repository->getOne($list->id);

        $this->assertInstanceOf(TodoList::class, $response);

        $this->assertEquals($list->toArray(), $response->toArray());
    }
}
