<?php

namespace App\Modules\TodoList\Requests;

use App\Modules\TodoList\Models\TodoList;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $todoList = TodoList::find($this->route('list'));
        return $this->user()->can('update',$todoList);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|max:255',
            'description' => 'string|max:1000'
        ];
    }
}
