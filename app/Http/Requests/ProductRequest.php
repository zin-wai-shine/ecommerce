<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => 'required|min:2',
            'price' => 'required|numeric|min:1|max:1000000000',
            'description' => 'required|min:3',
            'discount' => 'numeric|nullable',
            'product_status' => 'nullable|numeric',
            'category_id' => 'required|numeric|exists:categories,id'
        ];
    }
}
