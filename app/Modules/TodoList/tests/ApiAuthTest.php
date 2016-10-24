<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthTest extends TestCase
{
    public function testAuthentication()
    {
        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index'))
            ->seeJson(['error' => 'Unauthenticated.']);

        $user = \App\User::find(1);

        $token = $user->api_token;

        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index',['Authorization' => "Bearer $token"]))
            ->dontSeejson(['error' => 'Unauthenticated.']);
    }
}
