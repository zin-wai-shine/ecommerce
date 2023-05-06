<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // protected $primaryKey = 'permission_id';
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $fillable = ['name','module'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }


}
