<?php

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Services\ListItemService;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListItemServiceTest extends TestCase
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

        $response = $this->service->create($data, $this->list->id);

        $this->validate($response);
    }

    /**
     * @param $response
     */
    private function validate($response){
        $this->assertArrayHasKey('collection',$response);
        $this->assertArrayHasKey('version',$response['collection']);
        $this->assertArrayHasKey('href',$response['collection']);
    }
}
