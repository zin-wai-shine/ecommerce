<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueryValidRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sort' => 'nullable|valid_sort_column:categories',
            'order' => 'nullable|valid_order_column'
        ];
    }
}
