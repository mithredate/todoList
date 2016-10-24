<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 9:57 AM
 */

namespace App\Modules\TodoList\Repositories;


use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Models\TodoList;

class EloquentTodoListRepository implements RepositoryContract
{

    public function create($data)
    {
        $user_id = func_get_arg(1);
        $todoList = new TodoList();
        $todoList->user_id = $user_id;
        $todoList->fill($data);
        $todoList->save();
        return $todoList;
    }

    public function update($data, $id)
    {
        $todoList = TodoList::find($id);
        $todoList->fill($data);
        $todoList->save();
        return $todoList;
    }

    public function delete($id)
    {
        $todoList = TodoList::find($id);
        $todoList->delete();
        return true;
    }

    public function getAll()
    {
        $todoList = TodoList::all();
        return $todoList;
    }

    public function paginate($count)
    {
        $user_id = func_get_arg(1);
        return TodoList::where('user_id', $user_id)->paginate($count);
    }

    public function getOne($id)
    {
        return TodoList::find($id);
    }
}