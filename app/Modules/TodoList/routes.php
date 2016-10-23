<?php
/**
 * Created by PhpStorm.
 * User: mithredate
 * Date: 10/21/2016
 * Time: 1:45 PM
 */
Route::group(['middleware' => 'auth:api'], function(){
    Route::resource('list','TodoListController');
    Route::resource('list.items','ListItemController');
});