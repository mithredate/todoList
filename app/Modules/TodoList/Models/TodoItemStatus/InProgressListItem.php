<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 10:05 AM
 */

namespace App\Modules\TodoList\Models\TodoItemStatus;


use App\Modules\TodoList\Models\ListItem;

class InProgressListItem extends ListItem
{
    // override the base Model function
    public function newFromBuilder($attributes = array(), $connection = NULL)
    {
        if($attributes->status != 1) return null;
        return parent::newFromBuilder($attributes, $connection);
    }

    public function getStatusAttribute($value)
    {
        return 'inProgress';
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = 1;
    }
}