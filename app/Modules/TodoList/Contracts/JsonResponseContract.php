<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/22/2016
 * Time: 6:12 PM
 */

namespace App\Modules\TodoList\Contracts;


abstract class JsonResponseContract
{

    protected $href;

    protected $itemHref;

    protected $items;

    protected $template;

    protected $additionalItemHrefParams;
    /**
     * @param $href
     * @param array $template
     * @param null $items
     * @param null $itemHref
     * @param array $additionalItemHrefParams
     * @return array
     */
    public function render($href, $template = [], $items = null, $itemHref = null, $additionalItemHrefParams = []){
        $this->href = $href;
        $this->itemHref = $itemHref;
        $this->items = $items;
        $this->template = $template;
        $this->additionalItemHrefParams = $additionalItemHrefParams;
        $response = $this->renderMinimalResponse();
        if($this->haveItems() && $this->haveItemHref()){
            $response['collection']['items'] = $this->renderItems();
        }
        if($this->haveLinks()){
            $response['collection']['links'] = $this->renderLinks();
        }
        if($this->haveErrors()){
            $response['collection']['error'] = $this->renderErrors();
        }
        if($this->haveTemplate()){
            $response['collection']['template'] = [
                'data' => $this->renderTemplate()
            ];
        }
        return $response;
    }

    /**
     * @return array
     */
    private function renderMinimalResponse()
    {
        return [
            'collection' => [
                'version' => config('app.version'),
                'href' => $this->getHref()
            ]
        ];
    }

    protected abstract function haveItems();
    protected abstract function haveLinks();
    protected abstract function haveErrors();
    protected function haveTemplate(){
        return sizeof($this->template) > 0;
    }
    protected abstract function renderItems();
    protected abstract function renderLinks();
    protected abstract function renderErrors();
    protected function renderTemplate(){
        return array_filter($this->template, function($data){
            return strlen($data['prompt']) > 0;
        });
    }

    private function haveItemHref()
    {
        return ! is_null($this->itemHref) && strlen($this->itemHref) > 0;
    }

    /**
     * @return mixed
     */
    protected function getHref()
    {
        return $this->href;
    }


}