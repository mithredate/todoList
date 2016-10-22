<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoListTest extends TestCase
{

    use WithoutMiddleware,
        DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

    }

    public function testNewTodoList(){
        
    }
}
