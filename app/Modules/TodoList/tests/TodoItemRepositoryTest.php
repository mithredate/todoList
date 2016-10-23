<?php

use App\Modules\TodoList\Contracts\ListItemRepository;
use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoItemStatus\TodoListItem;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoItemRepositoryTest extends TestCase
{
    protected $user;
    protected $repository;
    protected $list;
    use DatabaseTransactions,
        WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->list = factory(\App\Modules\TodoList\Models\TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

        $this->repository = resolve(ListItemRepository::class);
    }

    public function testCreate()
    {
        $todoItem = factory(ListItem::class)->make([
            'created_by' => $this->user->id,
            'list_id' => $this->list->id
        ]);
        $data = array_only(
            $todoItem->toArray(),[
             'title','description','reminder','position','priority'
        ]);
        $item = $this->repository->create($data, $this->user->id, $this->list->id);
        $this->assertInstanceOf(ListItem::class, $item);
        $todo = TodoListItem::orderBy('created_at','desc')->first();
        $this->assertInstanceOf(TodoListItem::class, $todo);
        $this->seeInDatabase('list_items',$data);
    }
}
