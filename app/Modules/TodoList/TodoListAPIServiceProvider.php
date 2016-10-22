<?php

namespace App\Modules\TodoList;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TodoListAPIServiceProvider extends ServiceProvider
{

    protected $namespace = 'App\Modules\TodoList\Controllers';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api/v1',
        ], function ($router) {
            require dirname(__FILE__) . '/routes.php';
        });

        $this->app->bind('App\Modules\TodoList\Contracts\TodoListRepository',
            'App\Modules\TodoList\Repositories\EloquentTodoListRepository');
    }



    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
