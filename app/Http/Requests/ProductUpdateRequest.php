<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'title' => 'nullable|min:2',
            'price' => 'nullable|numeric|min:1|max:1000000000',
            'description' => 'nullable|min:3',
            'discount' => 'numeric|nullable',
            'product_status' => 'nullable|numeric',
            'category_id' => 'nullable|numeric|exists:categories,id'
        ];
    }
}
