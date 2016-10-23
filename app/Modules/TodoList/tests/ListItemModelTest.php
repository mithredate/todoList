<?php

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoList;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListItemModelTest extends TestCase
{

    use DatabaseTransactions, WithoutMiddleware;

    protected $list;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->list = factory(TodoList::class)->create([
            'user_id' => $this->user->id
        ]);
        $this->list->load('user');

    }

    public function testRelationships()
    {
        $item = factory(ListItem::class)->create([
            'list_id' => $this->list->id
        ]);

        $this->assertEquals($item->todoList->toArray(), $this->list->toArray());

        $this->assertEquals($item->created_by, $this->list->created_by);

        $this->assertEquals($item->created_at->format('Y-m-d H:i:s'), $item->modified_at->format('Y-m-d H:i:s'));

        $this->assertInstanceOf(User::class, $item->modifier);

        $this->assertEquals($item->modifier->name,
            $this->user->name);

        $this->assertInstanceOf(User::class,$item->user);

        $this->assertEquals($item->user->id, $this->user->id);
    }
}
