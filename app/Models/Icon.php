<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Icon extends Model
{
    use HasFactory;

    protected $fillable = ['id','icon_type','icon_name'];


    public function scopeGetIcon($query,$id =null)
    {
        $query->select(['id', DB::raw('CONCAT(icon_type, " ", icon_name) AS fullName')])
        ->when($id != null,function($q) use ($id){
            $q->where('id',$id);
        });
    }

    public function categories()
    {
       return $this->hasMany(category::class);
    }

}
