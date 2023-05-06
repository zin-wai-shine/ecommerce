<?php

namespace App\Http\Requests\customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddToCartRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id|integer',
            'item_count' => 'required|integer'
        ];
    }
}
