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

    public function update($data, $id);

    public function delete($id);

    public function getAll();

    public function paginate($count);

    public function getOne($id);
}