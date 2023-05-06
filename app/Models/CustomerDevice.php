<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDevice extends Model
{
    protected $fillable = ['action_type', 'device', 'date', 'ip', 'customer_id'];
    use HasFactory;
}
