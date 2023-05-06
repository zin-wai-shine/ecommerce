<?php

namespace App\Models;

use App\Traits\UsePhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,UsePhoto;

    protected $primaryKey = "customer_id";

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'password',
        'profile_photo'
    ];

    protected $hidden = [
        'password',
    ];

    public function getProfilePhotoAttribute($value)
    {
        return $this->attributes['profile_photo'] = $this->getImage($value,'customer');
    }

    public function scopeSearch($query){
        return $query->when(request('search'), function($q){
            $search = request('search');
            $q->orWhere("full_name","like","%$search%")
                ->orWhere("email","like","%$search%")
                    ->orWhere("phone_number","like","%$search%");
        });
    }

}
