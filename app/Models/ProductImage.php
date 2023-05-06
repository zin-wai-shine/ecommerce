<?php

namespace App\Models;

use App\Traits\UsePhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory,UsePhoto;

    protected $fillable =['path','name','product_id'];

    protected $hidden = ['updated_at'];

    public function getNameAttribute($value)
    {
        return $this->attributes['name'] = $this->getImage($value,'product');
    }
}
