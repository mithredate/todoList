<?php

namespace App\Modules\TodoList;

use App\Modules\TodoList\Contracts\ControllerServices;
use App\Modules\TodoList\Contracts\ListItemRepository;
use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Contracts\TodoListRepository;
use App\Modules\TodoList\Controllers\TodoListController;
use App\Modules\TodoList\Http\CollectionResponse;
use App\Modules\TodoList\Http\ItemResponse;
use App\Modules\TodoList\Models\TodoList;
use App\Modules\TodoList\Policies\TodoListPolicy;
use App\Modules\TodoList\Repositories\EloquentListItemRepository;
use App\Modules\TodoList\Repositories\EloquentTodoListRepository;
use App\Modules\TodoList\Services\ListItemService;
use App\Modules\TodoList\Services\TodoListService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ListItemRepositoryTest;
use ListItemServiceTest;
use TodoListRepositoryTest;
use TodoListServiceTest;

class TodoListAPIServiceProvider extends ServiceProvider
{

    protected $policies = [
        TodoList::class => TodoListPolicy::class
    ];

    protected $bindings = [
        ListItemService::class => [RepositoryContract::class => EloquentListItemRepository::class],
        TodoListService::class => [RepositoryContract::class => EloquentTodoListRepository::class],
    ];

    protected $singleton = [
        EloquentTodoListRepository::class,
        EloquentListItemRepository::class,
        CollectionResponse::class,
        ItemResponse::class,
        ListItemService::class,
        TodoListService::class
    ];


    protected $namespace = 'App\Modules\TodoList\Controllers';

    /**
     * Bootstrap the application services.
     *
     * @param ResponseFactory $factory
     * @param Guard $auth
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
        foreach($this->bindings as $when => $array){
            foreach ($array as $needs => $give){
                $this->app->when($when)->needs($needs)->give($give);
            }
        }

        $this->app->when(TodoListController::class)
            ->needs(ControllerServices::class)
            ->give(function($app){
                $service = resolve(TodoListService::class);
                $service->setUser(Auth::user());
                return $service;
            });

        foreach ($this->singleton as $className){
            $this->app->singleton($className);
        }

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



    }
}
