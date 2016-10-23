<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 12:15 AM
 */

namespace App\Modules\TodoList\Contracts;


interface ListItemRepository
{
    public function getAll();

    public function paginate($count);

    public function getOne($id);

    public function create($data, $user_id, $list_id);

    public function update($data, $id);

    public function delete($id);
}