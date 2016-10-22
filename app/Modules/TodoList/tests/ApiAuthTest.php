<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthTest extends TestCase
{
    public function testAuthentication()
    {
        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index'))
            ->see('Unauthenticated');

        $user = \App\User::find(1);

        $token = $user->api_token;

        $this->json('GET',action('\App\Modules\TodoList\Controllers\TodoListController@index',['api_token' => $token]))
            ->dontSee('Unauthenticated');
    }
}
