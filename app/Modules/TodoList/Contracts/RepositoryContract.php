<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 4:39 PM
 */

namespace App\Modules\TodoList\Contracts;


interface RepositoryContract
{

    public function getAll();

    public function paginate($count);

    public function create($data);

    public function update($data, $id);

    public function delete($id);

    public function getOne($id);

}