<?php

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoItemStatus\TodoListItem;
use App\Modules\TodoList\Repositories\EloquentListItemRepository;
use App\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

        $this->repository = resolve(EloquentListItemRepository::class);
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
        $item = $this->repository->create($data, $this->list->id);
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
        $this->createListItems(50, ['list_id' => $this->list->id]);

        $response = $this->repository->paginate(config('app.pagination_count'), $this->list->id);

        $this->assertEquals($response->count(), config('app.pagination_count'));

        $this->assertTrue($response->hasMorePages());

        $this->assertInstanceOf(Paginator::class, $response);
    }

    public function testGetOne(){
        $item = factory(ListItem::class)->create();

        $response = $this->repository->getOne($item->id);

        $this->assertInstanceOf(ListItem::class, $response);

        $this->assertEquals($response->id, $item->id);

    }


    private function createListItems($count, $override = [])
    {
        return factory(ListItem::class)->times($count)->create($override);
    }
}
