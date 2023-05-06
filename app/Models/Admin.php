<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $admin_default_id ;
     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function logins()
    {
        return $this->morphMany(Login::class, 'loginable');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // public function getLoginAtAttribute($value){
    //     return Carbon::parse($value)->format('U') * 1000;
    // }

    public function scopeUserProfile($query,$id = null)
    {
        $id = $id ?? Auth::user()->admin_id;
        // return $id ?? 'auth';
        return $query->select(
            'admins.admin_id',
            'admins.name',
            'admins.email',
            'roles.name as role_name',
            'logins.login_at as login_at',
            'device_logins.ip_address as ip' ,
            'device_logins.os as os' ,
            'device_logins.device as device' ,
            'device_logins.browser_agent as browser_agent' ,
            'device_logins.user_agent as user_agent' ,
        )
        ->where('admin_id',$id)
        ->join('roles','id','admins.role_id')
        ->join('logins','loginable_id','admins.admin_id')
        ->join('device_logins','login_id','logins.id')
        ->first();
    }
}
