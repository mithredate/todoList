<?php

namespace App\Modules\TodoList\Policies;

use App\Modules\TodoList\Models\TodoList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the todoList.
     *
     * @param  \App\User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function view(User $user, TodoList $todoList)
    {
        return $user->id === $todoList->user_id;
    }

    /**
     * Determine whether the user can create todoLists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the todoList.
     *
     * @param  \App\User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function update(User $user, TodoList $todoList)
    {
        return $user->id === $todoList->user_id;
    }

    /**
     * Determine whether the user can delete the todoList.
     *
     * @param  \App\User $user
     * @param TodoList $todoList
     * @return mixed
     */
    public function delete(User $user, TodoList $todoList)
    {
        return $user->id === $todoList->user_id;
    }
}
