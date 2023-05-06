<?php

namespace App\Models;

use App\Enum\ProductStatusEnum;
use App\Traits\UsePhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model
{
    use HasFactory,UsePhoto;

    protected $fillable = ['title','slug','price','discount','description','avg_rating','product_status','category_id'];

    protected $casts = [
        'price' => 'float',
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            $product->slug = Str::slug($product->title);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->title);
        });
    }

    public function getPriceAttribute($value)
    {
        return $this->attributes['price'] = number_format(floatval($value), 2, '.', '') * 1;
    }

    public function getPhotoNameAttribute($value)
    {
        return $this->attributes['photo_name'] = $this->getImage($value,'product');
    }

     public function getAvgRatingAttribute($value)
    {
        return  $this->attributes['avg_rating'] = number_format($value,2, '.', '') * 1;
    }

    public function getProductStatusAttribute($value )
    {

        $result = ProductStatusEnum::from($value)->toString();

        return  $this->attributes['product_status'] = $result;

    }

    public function oneRound($value)
    {
        return round($value, 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
