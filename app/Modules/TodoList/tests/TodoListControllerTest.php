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

    public function testCreateTodoListValidationFailure()
    {
        $this->validateTitleMaxLength();
        $this->validateTitleRequired();
        $this->validateDescriptionMaxLength();
    }

    public function testCreateTodoList()
    {
        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
        $mock->shouldReceive('create')->once()->withAnyArgs()->andReturn(anInstanceOf(TodoList::class));

        $todoList = array_only(factory(TodoList::class)->make([
            'user_id' => null
        ])->toArray(), ['title','description']);
        
        $this->post('api/v1/list',$todoList,$this->headers);

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

        $this->put('api/v1/list/' . $todoList->id,$modified,$this->headers);

        $this->assertResponseStatus(200);

    }

    public function testNonAuthorizedUpdateTodoList()
    {
        $todoList = factory(TodoList::class)->create();

        $this->put('api/v1/list/' . $todoList->id, ['title' => 'not authorized'], $this->headers);

        $this->assertResponseStatus(403);
    }

    public function testDeleteTodoList()
    {
        $todoList = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $mock = $this->mock(\App\Modules\TodoList\Contracts\TodoListRepository::class);
        $mock->shouldReceive('delete')->once()->with($todoList->id)->andReturn(true);

        $this->delete('api/v1/list/' . $todoList->id,[], $this->headers);

        $this->assertResponseStatus(202);

    }

    private function validateTitleMaxLength()
    {
        $this->validate('post',['title' => str_random(300)],'api/v1/list','The title may not be greater than 255 characters');
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->validate('put',['title' => str_random(300)],'api/v1/list/' . $list->id,'The title may not be greater than 255 characters');
    }

    private function validateTitleRequired()
    {
        $this->validate('post',[],'api/v1/list','The title field is required');
    }

    private function validate($method, $data, $uri, $message){
        $this->$method($uri, $data, $this->headers);
        $this->assertResponseStatus(422);
        $this->see($message);
    }

    private function validateDescriptionMaxLength()
    {
        $this->validate('post',['title' => str_random(200), 'description' => str_random(1200)],'api/v1/list','The description may not be greater than 1000 characters');
        $list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->validate('put',['title' => str_random(200), 'description' => str_random(1200)],'api/v1/list/' . $list->id,'The description may not be greater than 1000 characters');
    }

}
