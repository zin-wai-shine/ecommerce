<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'total_amount' => $this->total_amount,
            'payment_photo' => $this->payment_photo,
            'status' => $this->status,
            'date' => $date,
            'time' => $time
        ];
    }
}
