<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 4:15 PM
 */

namespace App\Modules\TodoList\Contracts;


interface ControllerServices
{
    public function index();

    public function show($id);

    public function create($data);

    public function update($data, $id);

    public function delete($id);
}