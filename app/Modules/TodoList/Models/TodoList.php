<?php

namespace App\Modules\TodoList\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{

    public static $template = [
        ["name" => "title", "value" => "", "prompt" => "Title"],
        ["name" => "description", "value" => "", "prompt" => "Description"],
        ["name" => "created_by", "value" => "", "prompt" => ""],
        ["name" => "created_at", "value" => "", "prompt" => ""]
    ];

    protected $with = ['user'];

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
        return $this->belongsTo(User::class);
    }

    public function getCreatedByAttribute($value)
    {
        return $this->user->name;
    }
}
