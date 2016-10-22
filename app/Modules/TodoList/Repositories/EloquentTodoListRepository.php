<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 9:57 AM
 */

namespace App\Modules\TodoList\Repositories;


use App\Modules\TodoList\Contracts\TodoListRepository;
use App\Modules\TodoList\Models\TodoList;
use Illuminate\Http\Request;

class EloquentTodoListRepository implements TodoListRepository
{

    public function create($data, $user_id)
    {
        $todoList = new TodoList();
        $todoList->user_id = $user_id;
        $todoList->fill($data);
        $todoList->save();
        return $todoList;
    }
}