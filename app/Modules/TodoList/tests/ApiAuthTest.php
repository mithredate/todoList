<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testUnauthenticated()
    {
        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index'))
            ->seeJson(['error' => ['title' => 'Unauthenticated', 'code' => 401, 'message' => 'Access is denied']])
            ->seeStatusCode(401);

    }

    public function testAuthenticated()
    {
        $user = factory(\App\User::class)->create();

        $token = $user->api_token;

        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index'),[],['Authorization' => "Bearer $token"])
            ->seeStatusCode(200);

    }
}
