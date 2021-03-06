<?php

namespace App\Modules\TodoList\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTodoListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|max:255|required',
            'description' => 'string|max:1000'
        ];
    }
}
