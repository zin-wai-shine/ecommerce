<?php

namespace App\Models;

use App\Constants\RoleConstant;
use App\Enum\RoleStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    use HasFactory;


    public function admin()
    {
       return $this->hasOne(Admin::class);
    }

    public function permissions()
    {
       return $this->belongsToMany(Permission::class,'role_permissions');
    }

    public static function getPermissionArray(?int $role_id = null)
    {
        $role_admin_id = $role_id ?? Auth::user()->role_id;
        $role = Role::where('id',$role_admin_id)->first();
        $permissions = RolePermission::where('role_id',$role->id)->pluck('permission_id')->toArray();
        return $permissions;
    }

    public static function canViewAll()
    {
        return Role::where('id',RoleStatusEnum::ADMIN->value)->first();
    }

    public function adminCheck()
    {
        if(Auth::user()->role_id == RoleStatusEnum::ADMIN->value)
        {
            return true;
        }
        return false;
    }
}
