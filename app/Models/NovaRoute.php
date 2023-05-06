<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovaRoute extends Model
{
    use HasFactory;

    protected $fillable = ['name','uri','require_permission'];
}
