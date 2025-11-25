<?php

namespace App\Domain\Rbac\Models;


use App\Domain\User\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\PermissionRegistrar;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;
    public const ADMIN_ROLE_ID = 1;

    public static  function newFactory(): RoleFactory
    {
        return new RoleFactory();
    }
}
