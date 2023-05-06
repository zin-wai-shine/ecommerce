<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OrderResource extends JsonResource
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
            'full_date' => $this->created_at->format('j/M/Y')
        ];

        $time = (object)[
            'hour' => $this->created_at->format('H'),
            'minute' => $this->created_at->format('i'),
            'second'=> $this->created_at->format('s'),
            'day_time'=> $this->created_at->format('A'),
            'full_time'=> $this->created_at->format('g:i:s A')
        ];

        $customer = Customer::where('customer_id', Auth::id())->first();

        return [
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'customer_name' => new CustomerResource($customer),
            'order_number' => $this->order_number,
            'status' => $this->status,
            'date' => $date,
            'time' => $time,
            'order_items' => OrderItemResource::collection($this->orderItems),
            'payment' => new PaymentResource($this->payment),
        ];
    }
}
