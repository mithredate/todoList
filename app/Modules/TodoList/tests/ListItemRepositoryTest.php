<?php

use App\Modules\TodoList\Contracts\ListItemRepository;
use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoItemStatus\TodoListItem;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ListItemRepositoryTest extends TestCase
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
        $listItem = factory(ListItem::class)->make([
            'list_id' => $this->list->id
        ]);
        $data = array_only(
            $listItem->toArray(),[
             'title','description','reminder','position','priority'
        ]);
        $item = $this->repository->create($data, $this->user->id, $this->list->id);
        $this->assertInstanceOf(ListItem::class, $item);
        $todo = TodoListItem::orderBy('created_at','desc')->first();
        $this->assertInstanceOf(TodoListItem::class, $todo);
        $this->seeInDatabase('list_items',$data);
    }

    public function testUpdate()
    {
        $listItem = factory(ListItem::class)->create([
            'list_id' => $this->list->id
        ]);

        $initial = array_only(
            $listItem->toArray(),[
            'title','description','reminder','position','priority'
        ]);

        $data = [
            'title' => 'modified list item title'
        ];

        $modified = $this->repository->update($data, $listItem->id);

        $this->assertInstanceOf(ListItem::class, $modified);

        $this->seeInDatabase('list_items',array_merge($initial, $data));
    }

    public function testDelete()
    {
        $listItem = factory(ListItem::class)->create([
            'list_id' => $this->list->id
        ]);

        $initial = array_only(
            $listItem->toArray(),[
            'title','description','reminder','position','priority'
        ]);

        $deleted = $this->repository->delete($listItem->id);

        $this->assertTrue($deleted);

        $this->dontSeeInDatabase('list_items',$initial);
    }

    public function testGetAll()
    {
        $this->createListItems(50);

        $response = $this->repository->getAll();
        
        $this->assertInstanceOf(Collection::class, $response);
    }

    public function testPaginate()
    {
        $this->createListItems(50);

        $response = $this->repository->paginate(config('app.pagination_count'));

        $this->assertEquals($response->count(), config('app.pagination_count'));

        $this->assertTrue($response->hasMorePages());

        $this->assertInstanceOf(Paginator::class, $response);
    }


    private function createListItems($count)
    {
        return factory(ListItem::class)->times($count)->create();
    }
}
