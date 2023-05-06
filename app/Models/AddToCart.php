<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'product_name', 'customer_id', 'customer_name', 'item_count', 'price'];
    protected $primaryKey = "cart_id";

    public function products(){
        return $this->belongsTo(Product::class);
    }


}
