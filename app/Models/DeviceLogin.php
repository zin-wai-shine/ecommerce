<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceLogin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
    'ip_address',
    'loggged_id',
    'device',
    'os',
    'browser_agent',
    'user_agent',
    'login_id',

    ];

    public function logins()
    {
        return $this->belongsTo(Login::class, 'loginable');
    }
}
