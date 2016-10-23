<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/23/2016
 * Time: 12:18 AM
 */

namespace App\Modules\TodoList\Repositories;


use App\Modules\TodoList\Contracts\RepositoryContract;
use App\Modules\TodoList\Models\ListItem;

class EloquentListItemRepository implements RepositoryContract
{

    public function getAll()
    {
        $items = ListItem::all();
        return $items;
    }

    public function paginate($count)
    {
        return ListItem::paginate($count);
    }

    public function getOne($id)
    {
        // TODO: Implement getOne() method.
    }

    public function create($data)
    {
        $list_id = func_get_arg(1);
        $item = new ListItem();
        $item->list_id = $list_id;
        $item->fill($data);
        $item->save();
        return $item;
    }

    public function update($data, $id)
    {
        $item = ListItem::find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = ListItem::find($id);
        $item->delete();
        return true;
    }
}