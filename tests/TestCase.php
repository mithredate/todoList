<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function mock($class){
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @param $response
     */
    protected function validateResponse($response){
        $this->assertArrayHasKey('collection',$response);
        $this->assertArrayHasKey('version',$response['collection']);
        $this->assertArrayHasKey('href',$response['collection']);
    }

    /**
     * @param $response
     */
    protected function validateResponseLinks($response)
    {
        $this->assertArrayHasKey('links', $response['collection']);
    }

    protected function validationTest($method, $data, $uri, $message){
        $this->json($method,$uri, $data);
        $this->assertResponseStatus(422);
        $this->see($message);
    }

    protected function validateResponseError($response)
    {
        $this->assertArrayHasKey('error',$response['collection']);
    }
}
