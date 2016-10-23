<?php

namespace App\Modules\TodoList\Models;

use App\Modules\TodoList\Models\TodoItemStatus\DoneListItem;
use App\Modules\TodoList\Models\TodoItemStatus\InProgressListItem;
use App\Modules\TodoList\Models\TodoItemStatus\TodoListItem;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    public $table = 'list_items';

    protected $dates = ['created_at','modified_at','reminder'];

    public $timestamps = false;

    protected $fillable = ['title','description', 'position','priority','reminder'];

    protected $state = [
        '0' => TodoListItem::class,
        '1' => InProgressListItem::class,
        '2' => DoneListItem::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($item){
            $item->modified_by = $item->created_by;
            $item->modified_at = $item->created_at = Carbon::now();
            foreach (array_flip($item->state) as $className => $statusCode){
                if($item instanceof $className){
                    $item->status = $statusCode;
                    break;
                }
            }
            $item->status = is_null($item->status) ? 0 : $item->status;
        });
    }

    public function creator(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class,'modified_by','id');
    }

    // override the base Model function
    public function newFromBuilder($attributes = array(), $connection = NULL)
    {
        if(isset($attributes->status)){
//            $class = 'App\Modules\TodoList\Models\TodoItemStatus\\' . ucwords(self::$type[$attributes->status]);
            $class = $this->state[$attributes->status];
            $instance = new $class;
            $instance->exists = true;
            $instance->setRawAttributes((array) $attributes, true);
            $instance->setConnection($connection ?: $this->connection);
            return $instance;
        }
        return parent::newFromBuilder($attributes, $connection);
    }

    // override the base Model function
    public static function hydrate(array $items, $connection = null)
    {
        $instance = (new static)->setConnection($connection);

        $items = array_map(function ($item) use ($instance)
        {
            return $instance->newFromBuilder($item);
        }, $items);
        $items = array_filter($items);
        return $instance->newCollection($items);
    }

}
