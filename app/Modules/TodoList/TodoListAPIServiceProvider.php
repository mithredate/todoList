<?php

namespace App\Modules\TodoList;

use App\Modules\TodoList\Contracts\TodoListRepository;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Policies\TodoListPolicy;
use App\Modules\TodoList\Repositories\EloquentTodoListRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TodoListAPIServiceProvider extends ServiceProvider
{

    protected $policies = [
        TodoList::class => TodoListPolicy::class
    ];

    protected $namespace = 'App\Modules\TodoList\Controllers';

    /**
     * Bootstrap the application services.
     *
     * @param ResponseFactory $factory
     */
    public function boot(ResponseFactory $factory)
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }

        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api/v1',
        ], function ($router) {
            require dirname(__FILE__) . '/routes.php';
        });

        $this->app->bind(TodoListRepository::class,
            EloquentTodoListRepository::class);

        $this->app->singleton(CollectionResponse::class);
        $this->app->singleton(ItemResponse::class);

        $factory->macro('collectionJson',function($value, $status) use ($factory){
           return $factory->make(json_encode($value))
               ->header('content-type','application/vnd.collection+json')
               ->setStatusCode($status);
        });
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
