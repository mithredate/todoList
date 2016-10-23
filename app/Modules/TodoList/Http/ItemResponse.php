<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 8:55 PM
 */

namespace App\Modules\TodoList\Http;


use App\Modules\TodoList\Contracts\JsonResponseContract;

class ItemResponse extends JsonResponseContract
{

    protected function haveItems()
    {
        return ! is_null($this->items);
    }

    protected function haveLinks()
    {
        return false;
    }

    protected function haveErrors()
    {
        return false;
    }



    protected function renderItems()
    {
        $data = [];
        foreach ($this->template as $k => $v) {
            $data[$k] = [];
            $data[$k]['name'] = $v['name'];
            $data[$k]['prompt'] = $v['prompt'];
            $value = $v['name'];
            $data[$k]['value'] = $this->items->$value;
        }
        $items = [
            'href' => action($this->itemHref, array_merge(['id' => $this->items->id], $this->additionalItemHrefParams)),
            'data' => $data
        ];
        return $items;
    }

    protected function renderLinks()
    {

    }

    protected function renderErrors()
    {

    }

}