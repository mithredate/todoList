<?php

namespace App\Modules\TodoList\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{

    protected $dates = ['created_at'];

    protected $fillable = ['title','description'];

    public $table = 'todo_list';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::creating(function($list){
           $list->created_at = Carbon::now();
        });
    }

    public function setDescriptionAttribute($value){
        $this->attributes['description'] = (strlen($value) > 0) ? $value : null;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
