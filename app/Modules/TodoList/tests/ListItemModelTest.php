<?php

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoList;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListItemModelTest extends TestCase
{

    protected $list;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);

    }

    public function testRelationships()
    {
        $item = factory(ListItem::class)->create([
            'created_by' => $this->user->id,
            'list_id' => $this->list->id
        ]);

        $this->assertEquals($item->todoList->toArray(), $this->list->toArray());
    }
}
