<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\Product;
use Cassandra\Custom;
use Illuminate\Http\Resources\Json\JsonResource;

class AddToCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $date = (object)[
            'day' =>  $this->created_at->format('j'),
            'month' => $this->created_at->format('M'),
            'year' => $this->created_at->format('Y'),
            'full_date' => $this->created_at->format('j/M/Y'),
        ];

        $time = (object)[
            'hour' => $this->created_at->format('H'),
            'minute' => $this->created_at->format('i'),
            'second'=> $this->created_at->format('s'),
            'day_time'=> $this->created_at->format('A'),
            'full_time'=> $this->created_at->format('g:i:s A')
        ];

        $product = Product::where('id', $this->product_id)->first();
        $customer = Customer::where('customer_id', $this->customer_id)->first();

        $product = (object)[
            'product_id' => $this->product_id,
            'product_name' => $product->title,
            'product_description' => $product->description,
            'item_count' => $this->item_count,
            'price' => $this->price,
            'total_price' => $this->item_count * $this->price,
        ];

        return [
            'cart_id' => $this->cart_id,
            'customer_id'=> $this->customer_id,
            'customer_name' => $customer->full_name,
            'product' => $product,
            'date' => $date,
            'time' => $time
        ];
    }
}
