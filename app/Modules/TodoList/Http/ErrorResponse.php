<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/24/2016
 * Time: 11:21 AM
 */

namespace App\Modules\TodoList\Http;


use App\Modules\TodoList\Contracts\JsonResponseContract;

class ErrorResponse extends JsonResponseContract
{

    protected function haveItems()
    {
        return false;
    }

    protected function haveLinks()
    {
        return false;
    }

    protected function haveErrors()
    {
        return true;
    }

    protected function renderItems()
    {
        return [];
    }

    protected function renderLinks()
    {
        return [];
    }

    protected function renderErrors()
    {
        return $this->error;
    }
}