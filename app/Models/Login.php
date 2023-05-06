<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable=['login_at'];

    public function getLoginAtAttribute($value){
        return Carbon::parse($value)->format('U') * 1000;
    }

    public function loginable()
    {
        return $this->morphTo();
    }


    public function device()
    {
        return $this->hasOne(DeviceLogin::class);
    }
}
