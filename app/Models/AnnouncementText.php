<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementText extends Model
{
    use HasFactory;

    protected $fillable = ['main_text','sub_text','section'];

    public function getSubTextAttribute($value)
    {
        return $this->attributes['sub_text'] = json_decode($value);
    }
}
