<?php

namespace App\Modules\TodoList\Contracts;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 10:20 AM
 */
interface TodoListRepository
{

    public function create($data, $user_id);
}