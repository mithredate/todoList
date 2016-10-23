<?php

use App\Modules\TodoList\Models\TodoList;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListControllerTest extends TestCase
{

    use DatabaseTransactions, WithoutMiddleware;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = User::first();

        $this->actingAs($this->user);
    }

    public function testCreateTodoListValidationFailure()
    {
        $this->validateTitleMaxLength();
        $this->validateTitleRequired();
        $this->validateDescriptionMaxLength();
    }

    public function testCreateTodoList()
    {

        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);

        $this->json('POST',action('\App\Modules\TodoList\Controllers\TodoListController@index'),$todoList);

        $this->assertResponseStatus(201);


    }

    public function testEditTodoList(){

        $todoList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $initial = $modified = array_only($todoList->toArray(),['title','description']);
        $modified['title'] = 'Updated todo list title';

        $this->json('PUT',action('\App\Modules\TodoList\Controllers\TodoListController@show',['id' => $todoList->id]), $modified);

        $this->assertResponseStatus(200);

    }

    public function testNonAuthorizedUpdateTodoList()
    {
        $todoList = factory(TodoList::class)->create();

        $this->json('PUT',action('\App\Modules\TodoList\Controllers\TodoListController@show',['id' => $todoList->id]), ['title' => 'not authorized']);

        $this->assertResponseStatus(403);
    }

    public function testDeleteTodoList()
    {
        $todoList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
        $mock->shouldReceive('delete')->once()->with($todoList->id)->andReturn(true);

        $this->json('DELETE',action('\App\Modules\TodoList\Controllers\TodoListController@show',['id' => $todoList->id]));

        $this->assertResponseStatus(204);

    }

    private function validateTitleMaxLength()
    {
        $this->validationTest('post',['title' => str_random(300)],'api/v1/list','The title may not be greater than 255 characters');
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->validationTest('put',['title' => str_random(300)],'api/v1/list/' . $list->id,'The title may not be greater than 255 characters');
    }

    private function validateTitleRequired()
    {
        $this->validationTest('post',[],'api/v1/list','The title field is required');
    }

    

    private function validateDescriptionMaxLength()
    {
        $this->validationTest('post',['title' => str_random(200), 'description' => str_random(1200)],'api/v1/list','The description may not be greater than 1000 characters');
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->validationTest('put',['title' => str_random(200), 'description' => str_random(1200)],'api/v1/list/' . $list->id,'The description may not be greater than 1000 characters');
    }

    public function testIndex(){
        factory(TodoList::class)->times(50)->create();
//        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
//        $mock->shouldReceive('paginate')->once()->withArgs([10,1]);
        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index'));
        $this->assertResponseStatus(200);
    }

    public function testShow()
    {
        $list = factory(TodoList::class)->create();
        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@show',['list' => $list->id]));
        $this->assertResponseStatus(200);
    }

}
