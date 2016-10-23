<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 8:16 PM
 */

namespace App\Modules\TodoList\Http;


use App\Modules\TodoList\Contracts\JsonResponseContract;
use Illuminate\Contracts\Pagination\Paginator;

class CollectionResponse extends JsonResponseContract
{

    protected function haveItems()
    {
        return ! is_null($this->items) && $this->items instanceof Paginator && $this->items->total() > 0;
    }

    protected function haveLinks()
    {
        return $this->items->total() > config('app.pagination_count');
    }

    protected function haveErrors()
    {
        return false;
    }


    protected function renderItems()
    {
        $items = [];
        foreach ($this->items as $key => $item) {
            $data = [];
            foreach ($this->template as $k => $v) {
                $data[$k] = [];
                $data[$k]['name'] = $v['name'];
                $data[$k]['prompt'] = $v['prompt'];
                $value = $v['name'];
                $data[$k]['value'] = $item->$value;
            }
            $items[] = [
                'href' => action($this->itemHref, array_merge(['id' => $item->id], $this->additionalItemHrefParams)),
                'data' => $data
            ];
        }
        return $items;
    }

    protected function renderLinks()
    {
        $links = $this->additionalLinks;
        if ($this->items->nextPageUrl()) {
            $links[] = [
                'rel' => 'next',
                'href' => $this->items->nextPageUrl()
            ];
        }
        if ($this->items->previousPageUrl()) {
            $links[] = [
                'rel' => 'prev',
                'href' => $this->items->previousPageUrl()
            ];
        }
        return $links;
    }

    protected function renderErrors()
    {
        return null;
    }
}