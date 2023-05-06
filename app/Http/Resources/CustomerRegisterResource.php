<?php

namespace App\Http\Resources;

use App\Traits\UsePhoto;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRegisterResource extends JsonResource
{
    use UsePhoto;

    protected $photo;
    public function getProfilePhotoAttribute($value)
    {
        return $this->photo = $this->getImage($value,'customer');
    }
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

        return[
            'customer_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name'=> $this->last_name,
            'full_name'=> $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'profile_photo' => $this->getProfilePhotoAttribute($this->profile_photo),
            'role_status'=>$this->role_status,
            'date' => $date,
            'time' => $time
        ];
    }
}
