<?php

use App\Modules\TodoList\Contracts\TodoItemRepository;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoItemRepositoryTest extends TestCase
{
    protected $user;
    protected $repository;
    use DatabaseTransactions,
        WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        $this->repository = resolve(TodoItemRepository::class);
    }

    public function testCreate()
    {
        $this->assertTrue(true);
    }
}
