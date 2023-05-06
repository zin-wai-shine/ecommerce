<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = "order_id";

    protected $fillable = ['customer_id', 'order_number'];

  /*  public function getCreatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d-m-Y H:i:s', strtotime($value));
    }*/

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment(){
        return $this->hasOne(Payment::class, 'order_id');
    }

}
