<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/24/2016
 * Time: 11:53 AM
 */

namespace App\Modules\TodoList\Traits;


use App\Modules\TodoList\Contracts\JsonResponseContract;
use Illuminate\Validation\UnauthorizedException;

trait ServiceAuthorization
{

    private function authorize($method, $user, $id, $model)
    {
        $item = $model::find($id);
        if ($user->cant($method, $item)) {
            throw new UnauthorizedException("You are not authorized to $method the resource", 403);
        }
    }

    

}