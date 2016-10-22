<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthTest extends TestCase
{
    public function testAuthentication()
    {
        $headers = ['accept' => 'application/json'];
        $this->get('api/v1/list', $headers)
            ->see('Unauthenticated');

        $user = \App\User::find(1);

        $token = $user->api_token;

        $this->call('GET','api/v1/list',['api_token' => $token],[],[],$headers);
        $this->dontSee('Unauthenticated');
    }
}
