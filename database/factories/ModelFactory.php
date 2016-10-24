<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Modules\TodoList\Models\ListItem;
use App\Modules\TodoList\Models\TodoItemStatus\DoneListItem;
use App\Modules\TodoList\Models\TodoItemStatus\InProgressListItem;
use App\Modules\TodoList\Models\TodoItemStatus\TodoListItem;
use App\Modules\TodoList\Models\TodoList;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'api_token' => bcrypt(str_random(10))
    ];
});

$factory->define(TodoList::class, function (Faker $faker){
   return [
       'title' => $faker->text(255),
       'description' => $faker->paragraph(),
       'user_id' => function () {
           return factory(User::class)->create()->id;
       }
   ];
});

$factory->define(ListItem::class, function(Faker $faker){
    return [
      'title' => $faker->text(255),
        'description' => $faker->paragraph(),
        'priority' => random_int(1,3),
        'position' => ListItem::count(),
        'reminder' => Carbon::tomorrow(),
        'list_id' => function(){
            return factory(TodoList::class)->create()->id;
        }
    ];
});

$factory->define(TodoListItem::class, function(Faker $faker) use ($factory){
    $item = $factory->raw(ListItem::class);
    return array_merge($item, ['status' => 0]);
});

$factory->define(InProgressListItem::class, function(Faker $faker) use ($factory){
    $item = $factory->raw(ListItem::class);
    return array_merge($item, ['status' => 1]);
});

$factory->define(DoneListItem::class, function(Faker $faker) use ($factory){
    $item = $factory->raw(ListItem::class);
    return array_merge($item, ['status' => 2]);
});