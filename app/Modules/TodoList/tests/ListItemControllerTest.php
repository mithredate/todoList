<?php

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Services\ListItemService;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListItemControllerTest extends TestCase
{
    protected $user;
    protected $list;
    protected $service;

    use DatabaseTransactions, WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        
        $this->service = resolve(ListItemService::class);
    }

    public function testCreate()
    {
        $item = factory(ListItem::class)->make([
            'list_id' => $this->list->id
        ]);

        $data = array_only($item->toArray(),[
            'title','description','position','priority','reminder'
        ]);

        $this->json('POST',action('\App\Modules\TodoList\Controllers\ListItemController@index',['list' => $this->list->id]),$data);

        $this->assertResponseStatus(201);
    }

    public function testIndex()
    {
        $this->json('GET',action('\App\Modules\TodoList\Controllers\ListItemController@index',['list' => $this->list->id]));

        $this->assertResponseStatus(200);
    }

    public function testUpdate()
    {
        $item = factory(ListItem::class)->create([
            'list_id' => $this->list->id
        ]);

        $data = [
            'title' => 'modified title'
        ];

        $this->json('PUT',action('\App\Modules\TodoList\Controllers\ListItemController@show',['list' => $this->list->id, 'items' => $item->id]), $data);

        $this->assertResponseStatus(200);
    }

    public function testShow()
    {
        $item = factory(ListItem::class)->create([
            'list_id' => $this->list->id
        ]);

        $this->json('GET',action('\App\Modules\TodoList\Controllers\ListItemController@show',['list' => $this->list->id, 'items' => $item->id]));

        $this->assertResponseStatus(200);
    }
}
